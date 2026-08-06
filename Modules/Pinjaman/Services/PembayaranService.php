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
use Modules\Pinjaman\Repositories\PinjamanRepository;

class PembayaranService {
    private PembayaranRepository $pembayaranRepository;
    private AngsuranRepository $angsuranRepository;
    private PinjamanRepository $pinjamanRepository;

    public function __construct(PembayaranRepository $pembayaranRepository, AngsuranRepository $angsuranRepository, PinjamanRepository $pinjamanRepository)
    {
        $this->pembayaranRepository = $pembayaranRepository;
        $this->angsuranRepository = $angsuranRepository;
        $this->pinjamanRepository = $pinjamanRepository;
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
                'jumlah_bayar'       => $validated['jumlah_bayar'],
                'bukti_pembayaran'   => $namaFile,
                'status_pembayaran'  => 'verifikasi',
            ];
            $pembayaran = $this->pembayaranRepository->create($dataPembayaran);

            $tunggakan = $this->angsuranRepository->getTunggakan(
                $angsuran->id_pinjaman,
                $angsuran->tanggal_jatuh_tempo
            );

            foreach ($tunggakan as $item) {
                $this->angsuranRepository->update([
                    'status_bayar' => 'verifikasi'
                ], $item->id);
            }

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
                'jumlah_bayar'       => $validated['jumlah_bayar'],
                'bukti_pembayaran'   => $namaFile,
                'status_pembayaran'  => 'verifikasi',
            ];

            $pembayaran = $this->pembayaranRepository->update($dataPembayaran, $pembayaran->id);


            $tunggakan = $this->angsuranRepository->getTunggakanGagal(
                $angsuran->id_pinjaman,
                $angsuran->tanggal_jatuh_tempo
            );

            foreach ($tunggakan as $item) {
                $this->angsuranRepository->update([
                    'status_bayar' => 'verifikasi'
                ], $item->id);
            }

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
                'jumlah_bayar'       => $validated['jumlah_bayar'],
                'bukti_pembayaran'   => null,
                'status_pembayaran'  => 'sukses',
            ];
            $pembayaran = $this->pembayaranRepository->create($dataPembayaran);

            $tunggakan = $this->angsuranRepository->getTunggakan(
                $angsuran->id_pinjaman,
                $angsuran->tanggal_jatuh_tempo
            );

            foreach ($tunggakan as $item) {
                $this->angsuranRepository->update([
                    'status_bayar' => 'lunas'
                ], $item->id);
            }
    
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
            $this->cekStatusPinjaman($angsuran->id_pinjaman);
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
            // Angsuran yang dibayar
            $angsuran = $this->angsuranRepository->getById(
                $fields,
                $pembayaran->id_angsuran
            );

            // Cari semua tunggakan sebelum angsuran ini
            $tunggakan = $this->angsuranRepository->getTunggakanVerifikasi(
                $angsuran->id_pinjaman,
                $angsuran->tanggal_jatuh_tempo
            );

            // Lunasi seluruh tunggakan
            foreach ($tunggakan as $item) {
                $this->angsuranRepository->update([
                    'status_bayar' => 'lunas'
                ], $item->id);
            }

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
            $this->cekStatusPinjaman($angsuran->id_pinjaman);
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
            ];
            $pembayaran = $this->pembayaranRepository->update(
                $data, $id
            );
            $fields = ['*'];
            // Angsuran yang dibayar
            $angsuran = $this->angsuranRepository->getById(
                $fields,
                $pembayaran->id_angsuran
            );

            // Cari semua tunggakan sebelum angsuran ini
            $tunggakan = $this->angsuranRepository->getTunggakanVerifikasi(
                $angsuran->id_pinjaman,
                $angsuran->tanggal_jatuh_tempo
            );

            // Lunasi seluruh tunggakan
            foreach ($tunggakan as $item) {
                $this->angsuranRepository->update([
                    'catatan_verifikasi' => $catatan,
                    'status_bayar' => 'gagal_verifikasi'
                ], $item->id);
            }

            $id_angsuran = $pembayaran->id_angsuran;
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
        
            $dataAngsuran = [
                'catatan_verifikasi' => $catatan,
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

    private function cekStatusPinjaman(int $pinjamanId): void
    {
        $belumLunas = $this->angsuranRepository
            ->existsBelumLunas($pinjamanId);

        if (! $belumLunas) {
            $this->pinjamanRepository->update([
                'status_pinjaman' => 'selesai',
            ], $pinjamanId);
        }
    }
}