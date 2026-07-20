<?php

namespace Modules\Pinjaman\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Pinjaman\Repositories\PengajuanPinjamanRepository;
use Modules\Pinjaman\Repositories\PersetujuanRepository;
use Modules\Pinjaman\Repositories\SkemaPinjamanRepository;

class PengajuanPinjamanService {
    private PengajuanPinjamanRepository $pengajuanPinjamanRepository;
    private PersetujuanRepository $persetujuanRepository;
    private SkemaPinjamanRepository $skemaPinjamanRepository;

    public function __construct(PengajuanPinjamanRepository $pengajuanPinjamanRepository, PersetujuanRepository $persetujuanRepository, SkemaPinjamanRepository $skemaPinjamanRepository)
    {
        $this->pengajuanPinjamanRepository = $pengajuanPinjamanRepository;
        $this->persetujuanRepository = $persetujuanRepository;
        $this->skemaPinjamanRepository = $skemaPinjamanRepository;
    }

    public function getAll($fields)
    {
        return $this->pengajuanPinjamanRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->pengajuanPinjamanRepository->getById($fields, $id);
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            // Simpan data pengajuan
            $jaminan = $data['jaminan'] ?? [];

            unset($data['jaminan']);

            $data['id_anggota'] = Auth::id();
            $data['status_pengajuan'] = 'menunggu';
            $pengajuanPinjaman = $this->pengajuanPinjamanRepository
                                        ->create($data);

            foreach ($jaminan as $item) {
                $file = $item['file'];
                $namaFile = time() . '_' . $file->getClientOriginalName();

                $file->move(
                    public_path('jaminan'),
                    $namaFile
                );

                $this->pengajuanPinjamanRepository
                    ->attachJaminan(
                        $pengajuanPinjaman,
                        $item['id_jaminan'],
                        $namaFile
                    );
            }
            DB::commit();
            return $pengajuanPinjaman;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $pengajuanPinjaman = $this->pengajuanPinjamanRepository->getById($fields, $id);
            

            $jaminan = $data['jaminan'] ?? [];

            unset($data['jaminan']);

            $data['id_anggota'] = Auth::id();
            $data['status_pengajuan'] = 'menunggu';
            $pengajuanPinjaman = $this->pengajuanPinjamanRepository->update($data, $id);

            foreach($jaminan as $item)
            {
                // user tidak upload file baru
                if (empty($item['file'])) {
                    continue;
                }

                // cek apakah jaminan sudah ada
                $jaminanLama = $this->pengajuanPinjamanRepository
                                ->getJaminan($pengajuanPinjaman, $item['id_jaminan']);

                // hapus file lama
                if ($jaminanLama && $jaminanLama->pivot->file_jaminan) {

                    $oldFile = public_path(
                        'jaminan/'.$jaminanLama->pivot->file_jaminan
                    );

                    $this->deleteFile($oldFile);
                }

                $file = $item['file']
                    ->store('jaminan');

                if ($jaminanLama) {
                    // update pivot
                    $this->pengajuanPinjamanRepository->updateJaminan(
                        $pengajuanPinjaman,
                        $item['id_jaminan'],
                        [
                            'file_jaminan' => $file,
                            'status_verifikasi' => null,
                            'keterangan' => null,
                        ]
                    );

                } else {
                    // attach jika belum ada
                    $this->pengajuanPinjamanRepository
                    ->attachJaminan(
                        $pengajuanPinjaman,
                        $item['id_jaminan'],
                        $file
                    );
                }
            }
            DB::commit();
            return $pengajuanPinjaman;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateStatusVerifikasi($id)
    {
        $data = ['status_pengajuan' => 'verifikasi'];
        return $this->pengajuanPinjamanRepository->update($data, $id);
    }

    public function teruskan($id)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $pengajuan = $this->pengajuanPinjamanRepository
                                ->getById($fields, $id);
            if ($pengajuan['status_pengajuan'] !== 'verifikasi') {
                throw new Exception('status tidak valid');
            }
            // Cek apakah skema membutuhkan jaminan
            if ($pengajuan->skemaPinjaman->jaminan === 'ada') {
                $belumVerifikasi = $this->pengajuanPinjamanRepository
                    ->masihAdaJaminanBelumTerverifikasi($id);
    
                if ($belumVerifikasi) {
                    throw new Exception(
                        'Semua file jaminan harus diverifikasi terlebih dahulu.'
                    );
                }
            }
            
            $updatePengajuanPinjaman = $this->pengajuanPinjamanRepository->update([
                'status_pengajuan' => 'persetujuan_awal'
                ], $pengajuan->id);
    
            $data = [
                'id_pengajuan' => $id,
                'role' => 'bendahara',
                'disetujui_oleh' => null,
                'status' => 'menunggu',
                'tanggal_disetujui' => null,
                'catatan' => null,
            ];
    
            $pengajuanUpdate = $this->pengajuanPinjamanRepository
                                    ->getById($fields, $id);
    
            if ($pengajuanUpdate['status_pengajuan'] == 'persetujuan_awal') {
                $persetujuan = $this->persetujuanRepository->create($data);
            }
            DB::commit();
            return $updatePengajuanPinjaman;
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

    // menampilkan riwayat pengajuan anggota
    public function getByAnggota($fields)
    {
        $user = Auth::id();
        $data = $this->pengajuanPinjamanRepository->getByAnggota($fields, $user);

        return $this->mappingPersetujuan($data);
    }

    private function mappingPersetujuan($pengajuanPinjaman)
    {
        foreach ($pengajuanPinjaman as $pengajuan) {

            $pengajuan->persetujuan_bendahara = null;
            $pengajuan->persetujuan_wadir = null;
            $pengajuan->persetujuan_ketua = null;

            foreach ($pengajuan->persetujuan as $persetujuan) {

                switch ($persetujuan->role) {

                    case 'bendahara':
                        $pengajuan->persetujuan_bendahara = $persetujuan;
                        break;

                    case 'wadir':
                        $pengajuan->persetujuan_wadir = $persetujuan;
                        break;

                    case 'ketua':
                        $pengajuan->persetujuan_ketua = $persetujuan;
                        break;
                }
            }
        }

        return $pengajuanPinjaman;
    }

    public function getDetail($id)
    {
        return $this->pengajuanPinjamanRepository->getDetail($id);
    }

    public function verifikasi($idPengajuan, $idJaminan)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $pengajuan = $this->pengajuanPinjamanRepository
                                ->getById($fields, $idPengajuan);
            if ($pengajuan['status_pengajuan'] !== 'verifikasi') {
                throw new Exception('status tidak valid');
            }

            $data = [
                'status_verifikasi' => 'verifikasi',
                'keterangan' => null,
            ];

            $this->pengajuanPinjamanRepository->updatePivotJaminan(
                $idPengajuan,
                $idJaminan,
                $data
            );
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function tolakVerifikasi($idPengajuan, $idJaminan, $keterangan)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $pengajuan = $this->pengajuanPinjamanRepository->getById($fields, $idPengajuan);
            if ($pengajuan['status_pengajuan'] !== 'verifikasi') {
                throw new Exception('status tidak valid');
            }

            $data = [
                'status_verifikasi' => 'ditolak',
                'keterangan' => $keterangan,
            ];
            
            $this->pengajuanPinjamanRepository->updatePivotJaminan(
                $idPengajuan,
                $idJaminan,
                $data
            );

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function simpanRevisi($id, $data)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $pengajuan = $this->pengajuanPinjamanRepository
                ->getById($fields, $id);

            foreach ($data['jaminan'] as $idJaminan => $file) {

                if (!$file) {
                    continue;
                }

                $pivot = $this->pengajuanPinjamanRepository
                            ->getPivotJaminan(
                                $pengajuan->id,
                                $idJaminan
                            );

                /*
                |-----------------------------------------
                | Hapus file lama
                |-----------------------------------------
                */

                $oldFile = public_path(
                    'jaminan/'.$pivot->file_jaminan
                );

                $this->deleteFile($oldFile);

                /*
                |-----------------------------------------
                | Upload file baru
                |-----------------------------------------
                */

                $namaFile = time() . '_' . $file->getClientOriginalName();

                $file->move(
                    public_path('jaminan'),
                    $namaFile
                );

                /*
                |-----------------------------------------
                | Update database
                |-----------------------------------------
                */

                $this->pengajuanPinjamanRepository
                    ->updatePivotJaminan(
                        $pengajuan->id,
                        $idJaminan,
                        [
                            'file_jaminan' => $namaFile,
                            'status_verifikasi' => 'menunggu',
                            'keterangan' => null,
                        ]
                    );

            }

            /*
            |-----------------------------------------
            | Update status pengajuan
            |-----------------------------------------
            */

            $dataPengajuan = 
            [
                'status_pengajuan' => 'verifikasi'
            ];

            $this->pengajuanPinjamanRepository
                ->update(
                    $dataPengajuan,
                    $pengajuan->id,
                );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];

            $pengajuan = $this->pengajuanPinjamanRepository->getById($fields, $id);

            if($pengajuan->skemaPinjaman->jaminan == 'ada') {
                foreach ($pengajuan->jaminan as $jaminan) {

                    $pivot = $jaminan->pivot;

                    if ($pivot->file_jaminan) {

                        $oldFile = public_path(
                            'jaminan/' . $pivot->file_jaminan
                        );

                        $this->deleteFile($oldFile);
                    }
                }

                // Hapus relasi pivot
                $this->pengajuanPinjamanRepository->detachJaminan($id);
            }

            $this->pengajuanPinjamanRepository->delete($id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}