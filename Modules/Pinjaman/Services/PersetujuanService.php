<?php

namespace Modules\Pinjaman\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Pinjaman\Repositories\AngsuranRepository;
use Modules\Pinjaman\Repositories\PengajuanPinjamanRepository;
use Modules\Pinjaman\Repositories\PersetujuanRepository;
use Modules\Pinjaman\Repositories\PinjamanRepository;
use Modules\Pinjaman\Repositories\SkemaPinjamanRepository;

class PersetujuanService {

    private PersetujuanRepository $persetujuanRepository;
    private PinjamanRepository $pinjamanRepository;
    private PengajuanPinjamanRepository $pengajuanPinjamanRepository;
    private SkemaPinjamanRepository $skemaPinjamanRepository;
    private AngsuranRepository $angsuranRepository;

    public function __construct(
        PersetujuanRepository $persetujuanRepository, 
        PinjamanRepository $pinjamanRepository, 
        PengajuanPinjamanRepository $pengajuanPinjamanRepository, 
        SkemaPinjamanRepository $skemaPinjamanRepository, 
        AngsuranRepository $angsuranRepository
    )
        {
            $this->persetujuanRepository = $persetujuanRepository;
            $this->pinjamanRepository = $pinjamanRepository;
            $this->pengajuanPinjamanRepository = $pengajuanPinjamanRepository;
            $this->skemaPinjamanRepository = $skemaPinjamanRepository;
            $this->angsuranRepository = $angsuranRepository;
        }

    public function getByRole($role)
    {
        return $this->persetujuanRepository->getByRole($role);
    }

    public function getById($fields, $id)
    {
        return $this->persetujuanRepository->getById($fields, $id);
    }

    public function updateStatusBendaharaSetuju($id)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'catatan' => null,
            ];


            $updatePersetujuan = $this->persetujuanRepository->update($updateData, $id);

            $dataBaru = [
                'id_pengajuan' => $persetujuan->id_pengajuan,
                'role' => 'wadir',
                'disetujui_oleh' => null,
                'status' => 'menunggu',
                'tanggal_disetujui' => null,
                'catatan' => null,
            ];

            $persetujuanBaru = $this->persetujuanRepository->create($dataBaru);

            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateStatusBendaharaTidakSetuju($data, $id)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'ditolak',
                'tanggal_disetujui' => now(),
                'catatan' => $data['catatan'],
            ];
    
            $updatePersetujuan = $this->persetujuanRepository->update($updateData, $id);
    
            $dataPengajuan = [
                'status_pengajuan' => 'ditolak',
            ];
    
            $pengajuan = $this->pengajuanPinjamanRepository->update(
                $dataPengajuan, $persetujuan['id_pengajuan']
            );
    
            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateStatusWadirSetuju($id)
    {
        DB::beginTransaction();
        try {

            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'catatan' => null,
            ];
    
            $updatePersetujuan = $this->persetujuanRepository->update($updateData, $id);
    
            $dataBaru = [
                'id_pengajuan' => $persetujuan->id_pengajuan,
                'role' => 'ketua',
                'disetujui_oleh' => null,
                'status' => 'menunggu',
                'tanggal_disetujui' => null,
                'catatan' => null,
            ];
    
            $persetujuanBaru = $this->persetujuanRepository->create($dataBaru);
    
            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateStatusWadirTidakSetuju($data, $id)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'ditolak',
                'tanggal_disetujui' => now(),
                'catatan' => $data['catatan'],
            ];
    
            $updatePersetujuan = $this->persetujuanRepository->update($updateData, $id);
    
            $dataPengajuan = [
                'status_pengajuan' => 'ditolak',
            ];
    
            $pengajuan = $this->pengajuanPinjamanRepository->update(
                $dataPengajuan, $persetujuan['id_pengajuan']
            );
    
            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateStatusKetuaSetuju($id)
    {
        DB::beginTransaction();
        try {
            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'catatan' => null,
            ];
    
            $updatePersetujuan = $this->persetujuanRepository->update(
                $updateData, $id
            );
    
            $dataPengajuan = [
                'status_pengajuan' => 'persetujuan_akhir',
            ];
    
            $pengajuan = $this->pengajuanPinjamanRepository->update(
                $dataPengajuan, $persetujuan['id_pengajuan']
            );
    
            $id_pengajuan = $persetujuan['id_pengajuan'];
            $pengajuan = $this->pengajuanPinjamanRepository->getById(
                $fields, $id_pengajuan
            );
    
            $id_skema_pinjaman = $pengajuan['id_skema_pinjaman'];
            $skemaPinjaman = $this->skemaPinjamanRepository->getById(
                $fields, $id_skema_pinjaman
            );
    
            $jumlah_bunga = $this->hitungBunga(
                $pengajuan['jumlah_pengajuan'], 
                $skemaPinjaman['bunga'], 
                $pengajuan['lama_angsuran']
            );
            $total_pinjaman = $jumlah_bunga + $pengajuan['jumlah_pengajuan'];
    
            $dataPinjaman = [
                'id_pengajuan' => $persetujuan['id_pengajuan'],
                'jumlah_disetujui' => $pengajuan['jumlah_pengajuan'],
                'jumlah_bunga' => $jumlah_bunga,
                'total_pinjaman' => $total_pinjaman,
                'tanggal_disetujui' => now(),
                'status_pinjaman' => 'belum_aktif',
            ];
            $pinjaman = $this->pinjamanRepository->create($dataPinjaman);
    
            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStatusKetuaTidakSetuju($data, $id)
    {
        DB::beginTransaction();
        try {

            $fields = ['*'];
            $persetujuan = $this->persetujuanRepository->getById($fields, $id);
            $updateData = [
                'disetujui_oleh' => Auth::id(),
                'status' => 'ditolak',
                'tanggal_disetujui' => now(),
                'catatan' => $data['catatan'],
            ];
    
            $updatePersetujuan = $this->persetujuanRepository->update(
                $updateData, $id
            );
    
            $dataPengajuan = [
                'status_pengajuan' => 'ditolak',
            ];
    
            $pengajuan = $this->pengajuanPinjamanRepository->update(
                $dataPengajuan, $persetujuan['id_pengajuan']
            );
    
            DB::commit();
            return $updatePersetujuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function persetujuanAkhir($dokumen, $id)
    {
        $namaFile = null;

        if ($dokumen) {
            $namaFile = time() . '_' . $dokumen->getClientOriginalName();
            $dokumen->move(public_path('dokumen_pinjaman'), $namaFile);
        }

        $data = [
            'status_pengajuan' => 'pencairan',
            'dokumen_ttd'      => $namaFile,
        ];

        return $this->pengajuanPinjamanRepository->update($data, $id);
    }

    public function getPencairan($fields)
    {
        return $this->pengajuanPinjamanRepository->getPencairan($fields);
    }

    public function pencairan($fields, $id)
    {
        DB::beginTransaction();
        
        try {
            $pinjaman = $this->pinjamanRepository->getById($fields, $id);
            $data = [
                'status_pinjaman' => 'aktif',
            ];
            $updatePinjaman = $this->pinjamanRepository->update($data, $id);
        
            $fields = ['*'];
            $pengajuan = $this->pengajuanPinjamanRepository->getById(
                $fields, $pinjaman['id_pengajuan']
            );

            $dataPengajuan = [
                'status_pengajuan' => 'disetujui',
            ];
            $updatePengajuan = $this->pengajuanPinjamanRepository->update(
                $dataPengajuan, $pengajuan['id']
            );

            $lama_angsuran = $pengajuan['lama_angsuran'];
            $jumlah_angsuran = $pinjaman['total_pinjaman'] / $lama_angsuran;
            
            // Tanggal jatuh tempo pertama
            $tanggal_jatuh_tempo = Carbon::now()->addMonth();
        
            // Generate data angsuran
            for ($i = 1; $i <= $lama_angsuran; $i++) {
                $dataAngsuran = [
                    'id_pinjaman' => $pinjaman['id'],
                    'angsuran_ke' => $i,
                    'jumlah_angsuran' => $jumlah_angsuran,
                    'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo->copy()->
                        addMonths($i - 1),
                    'status_bayar' => 'belum_bayar',
                ];
                $angsuran = $this->angsuranRepository->create($dataAngsuran);
            }
            DB::commit();
            return $updatePinjaman;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hitungBunga($nominal, $bunga, $tenor)
    {
        $jumlah_bunga = ($nominal * $bunga / 100) * $tenor;
        return $jumlah_bunga;
    }
}