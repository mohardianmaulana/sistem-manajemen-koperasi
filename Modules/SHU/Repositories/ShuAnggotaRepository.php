<?php

namespace Modules\SHU\Repositories;

use App\Models\Core\User;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\SHU\Entities\ShuAnggota;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

class ShuAnggotaRepository
{
    public function getAll($idAnggota=null)
    {
        $query = ShuAnggota::with('user');
        if ($idAnggota !== null) {
        $query->where('id_anggota', $idAnggota);
        }
        return $query->paginate(5);
    }
    
    public function getShuByTahun($tahun)
    {
        return ShuKoperasi::where('tahun', $tahun)->first();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function totalSimpananSemua($tahun)
    {
        $wajib = SimpananWajib::whereYear('periode', $tahun)
            ->sum('nilai');

        $sukarela = SimpananSukarela::whereYear('periode', $tahun)
            ->sum('nilai');

        return  $wajib + $sukarela;
    }

    public function totalSimpananAnggota($idAnggota, $tahun)
    {
        $wajib = SimpananWajib::where('id_anggota', $idAnggota)
            ->whereYear('periode', $tahun)
            ->sum('nilai');

        $sukarela = SimpananSukarela::where('id_anggota', $idAnggota)
            ->whereYear('periode', $tahun)
            ->sum('nilai');

        return  $wajib + $sukarela;
    }

    public function totalJasaPinjamanSemua($tahun)
    {
        return Pinjaman::where('status_pinjaman', 'selesai')
            ->whereYear('tanggal_disetujui', $tahun)
            ->sum('jumlah_bunga');
    }

    public function totalJasaPinjamanAnggota($idAnggota, $tahun)
    {
        return Pinjaman::join(
                'pengajuan_pinjaman',
                'pengajuan_pinjaman.id',
                '=',
                'pinjaman.id_pengajuan'
            )
            ->where('pengajuan_pinjaman.id_anggota', $idAnggota)
            ->where('pinjaman.status_pinjaman', 'selesai')
            ->whereYear('pinjaman.tanggal_disetujui', $tahun)
            ->sum('pinjaman.jumlah_bunga');
    }

    public function simpanShu(
        $idAnggota,
        $tahun,
        $shuSimpanan,
        $shuPinjaman
    ) {
        return ShuAnggota::updateOrCreate(

            [
                'id_anggota' => $idAnggota,
                'tahun'      => $tahun,
            ],

            [
                'shu_simpanan' => $shuSimpanan,
                'shu_pinjaman' => $shuPinjaman,
                'shu_anggota'  => $shuSimpanan + $shuPinjaman,
                'tanggal'      => now(),
            ]

        );
    }
}