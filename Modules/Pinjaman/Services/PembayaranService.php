<?php

namespace Modules\Pinjaman\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Http\Requests\PembayaranRequest;
use Modules\Pinjaman\Repositories\AngsuranRepository;
use Modules\Pinjaman\Repositories\PembayaranRepository;

class PembayaranService {
    private PembayaranRepository $pembayaranRepository;
    private AngsuranRepository $angsuranRepository;

    public function __construct(PembayaranRepository $pembayaranRepository, AngsuranRepository $angsuranRepository)
    {
        $this->pembayaranRepository = $pembayaranRepository;
        $this->angsuranRepository = $angsuranRepository;
    }

    public function getAll($fields)
    {
        return $this->pembayaranRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->pembayaranRepository->getById($fields, $id);
    }

    public function createManual(PembayaranRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $validated = $request->validated();
            $angsuran = $this->angsuranRepository->getById($fields, $validated['id_angsuran']);

            // Simpan file bukti pembayaran
            $namaFile = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $namaFile = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();

                $request->file('bukti_pembayaran')
                    ->move(public_path('bukti_pembayaran'), $namaFile);
            }

            // Data yang akan disimpan
            $dataPembayaran = [
                'id_angsuran'        => $angsuran->id,
                'jenis_pembayaran'   => 'manual',
                'tanggal_bayar'      => Carbon::now(),
                'jumlah_bayar'       => $angsuran->jumlah_angsuran,
                'bukti_pembayaran'   => $namaFile,
                'status_pembayaran'  => 'verifikasi',
            ];
            $pembayaran = $this->pembayaranRepository->create($dataPembayaran);

            $dataAngsuran = [
                'status_bayar' => 'verifikasi'
            ];
            $angsuran = $this->angsuranRepository->update($dataAngsuran, $validated['id_angsuran']);
            DB::commit();
            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createUlangManual(PembayaranRequest $request)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $validated = $request->validated();
            $angsuran = $this->angsuranRepository->getById($fields, $validated['id_angsuran']);

            // Cari pembayaran sebelumnya yang gagal
            $pembayaran = $this->pembayaranRepository->getPembayaran($angsuran->id);

            // Simpan file bukti pembayaran
            $namaFile = null;
            if ($request->hasFile('bukti_pembayaran')) {
                if($pembayaran && $pembayaran->bukti_pembayaran)
                {
                    $oldFile = public_path(
                        'bukti_pembayaran/'.$pembayaran->bukti_pembayaran
                    );
    
                    $this->deleteFile($oldFile);
                    $namaFile = time() . '_' . $request->file('bukti_pembayaran')->getClientOriginalName();
    
                    $request->file('bukti_pembayaran')
                        ->move(public_path('bukti_pembayaran'), $namaFile);
                }
            }

            // Data yang akan disimpan
            $dataPembayaran = [
                'id_angsuran'        => $angsuran->id,
                'jenis_pembayaran'   => 'manual',
                'tanggal_bayar'      => Carbon::now(),
                'jumlah_bayar'       => $angsuran->jumlah_angsuran,
                'bukti_pembayaran'   => $namaFile,
                'status_pembayaran'  => 'verifikasi',
                'catatan'            => null,
            ];

            $pembayaran = $this->pembayaranRepository->update($dataPembayaran, $pembayaran->id);

            $dataAngsuran = [
                'status_bayar' => 'verifikasi'
            ];

            $angsuran = $this->angsuranRepository->update($dataAngsuran, $validated['id_angsuran']);

            DB::commit();

            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function deleteFile($filePath)
    {
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    public function createAuto(PembayaranRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $angsuran = Angsuran::findorFail($validated['id_angsuran']);

            // Data yang akan disimpan
            $dataPembayaran = [
                'id_angsuran'        => $angsuran->id,
                'jenis_pembayaran'   => 'auto-debet',
                'tanggal_bayar'      => Carbon::now(),
                'jumlah_bayar'       => $angsuran->jumlah_angsuran,
                'bukti_pembayaran'   => null,
                'status_pembayaran'  => 'sukses',
            ];
            $pembayaran = $this->pembayaranRepository->create($dataPembayaran);
    
            $fields = ['*'];
            $id_angsuran = $pembayaran->id_angsuran;
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
    
            $data = [
                'status_bayar' => 'lunas',
            ];
            $updateAngsuran = $this->angsuranRepository->update(
                $data, $id_angsuran
            );
            DB::commit();
            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id)
    {
        DB::beginTransaction();
        try {
            $data = ['status_pembayaran' => 'sukses'];
            $pembayaran = $this->pembayaranRepository->update(
                $data, $id
            );
    
            $fields = ['*'];
            $id_angsuran = $pembayaran->id_angsuran;
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
    
            $dataAngsuran = [
                'status_bayar' => 'lunas',
            ];
            $updateAngsuran = $this->angsuranRepository->update(
                $dataAngsuran, $id_angsuran
            );
            DB::commit();
            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function gagalUpdate($catatan, $id)
    {
        DB::beginTransaction();
        try {
            $data = [
                'status_pembayaran' => 'ditolak',
                'catatan' => $catatan,
            ];
            $pembayaran = $this->pembayaranRepository->update(
                $data, $id
            );
            $fields = ['*'];
            $id_angsuran = $pembayaran->id_angsuran;
        
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
        
            $dataAngsuran = [
                'status_bayar' => 'gagal_verifikasi',
            ];
        
            $updateAngsuran = $this->angsuranRepository->update(
                $dataAngsuran, $id_angsuran
            );

            DB::commit();

            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}