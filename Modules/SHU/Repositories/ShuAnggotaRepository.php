<?php

namespace Modules\SHU\Repositories;

use App\Models\Core\User;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\SHU\Entities\ShuAnggota;
use Modules\SHU\Entities\ShuKoperasi;
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
    
    public function getShuKoperasi(
    $periodeAwal,
    $periodeAkhir
    )
    {
        return ShuKoperasi::where('periode_awal', $periodeAwal )
            ->where('periode_akhir', $periodeAkhir)
            ->first();
    }

    public function getUsers()
    {
        return User::role('anggota')->get();
    }

    public function totalSimpananSemua(
    $periodeAwal,
    $periodeAkhir
    )
    {
       {
            $wajib = SimpananWajib::whereBetween('periode',[$periodeAwal, $periodeAkhir])
            ->sum('nilai');

            $sukarela = SimpananSukarela::whereBetween('periode',[$periodeAwal, $periodeAkhir])
            ->sum('nilai');

            return $wajib + $sukarela;
        }
    }

   public function totalSimpananAnggota(
    $idAnggota,
    $periodeAwal,
    $periodeAkhir
    )
    {
        $wajib = SimpananWajib::where('id_anggota', $idAnggota)
            ->whereBetween('periode',[$periodeAwal, $periodeAkhir])
            ->sum('nilai');

        $sukarela = SimpananSukarela::where('id_anggota', $idAnggota)
            ->whereBetween('periode',[$periodeAwal, $periodeAkhir])
            ->sum('nilai');

        return $wajib + $sukarela;
    }

    public function totalJasaPinjamanSemua(
    $periodeAwal,
    $periodeAkhir
    )
    {
        $total = 0;

        $pinjaman = Pinjaman::with('pengajuan')
            ->whereBetween('tanggal_disetujui',[$periodeAwal, $periodeAkhir])
            ->get();

        foreach ($pinjaman as $item) {
            $bungaPerAngsuran =
                $item->jumlah_bunga /
                $item->pengajuan->lama_angsuran;

            $jumlahLunas = Angsuran::where('id_pinjaman', $item->id)
                ->where('status_bayar','lunas')
                ->whereBetween('tanggal_jatuh_tempo',[$periodeAwal, $periodeAkhir])
                ->count();

            $total +=
                $bungaPerAngsuran *
                $jumlahLunas;
        }

        return round($total);
    }

    public function totalJasaPinjamanAnggota(
    $idAnggota,
    $periodeAwal,
    $periodeAkhir
    ) {
        $total = 0;

        $pinjaman = Pinjaman::with('pengajuan')
            ->join(
                'pengajuan_pinjaman',
                'pengajuan_pinjaman.id',
                '=',
                'pinjaman.id_pengajuan'
            )
            ->where(
                'pengajuan_pinjaman.id_anggota',
                $idAnggota
            )
            ->whereBetween(
                'pinjaman.tanggal_disetujui',
                [$periodeAwal, $periodeAkhir]
            )
            ->select('pinjaman.*')
            ->get();

        foreach ($pinjaman as $item) {
            $bungaPerAngsuran =
                $item->jumlah_bunga /
                $item->pengajuan->lama_angsuran;

            $jumlahLunas = Angsuran::where(
                    'id_pinjaman',
                    $item->id
                )
                ->where(
                    'status_bayar',
                    'lunas'
                )
                ->whereBetween(
                    'tanggal_jatuh_tempo',
                    [$periodeAwal, $periodeAkhir]
                )
                ->count();
            $total +=
                $bungaPerAngsuran *
                $jumlahLunas;
        }

        return round($total);
    }

   public function simpanShu(
    $idAnggota,
    $periodeAwal,
    $periodeAkhir,
    $shuSimpanan,
    $shuPinjaman,
    $jasaPengurus,
    $shuAnggota,
    $pajak
    )
    {
        return ShuAnggota::updateOrCreate(

            [
                'id_anggota'   => $idAnggota,
                'periode_awal' => $periodeAwal,
                'periode_akhir'=> $periodeAkhir,
            ],

            [
                'shu_simpanan'  => round($shuSimpanan),

                'shu_pinjaman'  => round($shuPinjaman),

                'jasa_pengurus' => round($jasaPengurus),

                'shu_anggota'   => round($shuAnggota),

                'pajak'         => round($pajak),
            ]

        );
        
    }
}