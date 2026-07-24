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
    public function getAll($idAnggota = null)
    {
        $query = ShuAnggota::with('user');

        if ($idAnggota !== null) {
            $query->where('id_anggota', $idAnggota);
        }

        return $query
            ->orderByDesc('periode_akhir')
            ->paginate(10);
    }

    public function getSummary($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->orderByDesc('periode_akhir')
            ->first();
    }

    public function getTotalShu($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->sum('shu_anggota');
    }
    

    public function getTotalShuSimpanan($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->sum('shu_simpanan');
    }

    public function getTotalShuPinjaman($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->sum('shu_pinjaman');
    }

    public function getTotalPajak($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->sum('pajak');
    }

    public function getRiwayat($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->orderByDesc('periode_akhir')
            ->paginate(10);
    }

    public function getByPeriode($idAnggota, $tahun)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->whereYear('periode_akhir', $tahun)
            ->first();
    }

    public function getDaftarTahun($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->selectRaw('YEAR(periode_akhir) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');
    }

    public function getGrafik($idAnggota)
    {
        return ShuAnggota::where('id_anggota', $idAnggota)
            ->orderBy('periode_akhir')
            ->get([
                'periode_akhir',
                'shu_anggota'
            ]);
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
    ) {
        $total = 0;

        $pinjaman = Pinjaman::with('pengajuan')->get();

        foreach ($pinjaman as $item) {

            // Lewati jika lama angsuran tidak ada
            if (!$item->pengajuan || $item->pengajuan->lama_angsuran <= 0) {
                continue;
            }

            // Bunga per angsuran
            $bungaPerAngsuran =
                $item->jumlah_bunga /
                $item->pengajuan->lama_angsuran;

            // Jumlah angsuran yang sudah dibayar pada periode
            $jumlahLunas = Angsuran::where('id_pinjaman', $item->id)
                ->where('status_bayar', 'lunas')
                ->whereBetween(
                    'tanggal_jatuh_tempo',
                    [$periodeAwal, $periodeAkhir]
                )
                ->count();

            // Jasa pinjaman yang sudah diterima koperasi
            $total += $bungaPerAngsuran * $jumlahLunas;
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
            ->select('pinjaman.*')
            ->get();

        foreach ($pinjaman as $item) {

            // Lewati jika lama angsuran tidak ada
            if (!$item->pengajuan || $item->pengajuan->lama_angsuran <= 0) {
                continue;
            }

            // Bunga per angsuran
            $bungaPerAngsuran =
                $item->jumlah_bunga /
                $item->pengajuan->lama_angsuran;

            // Jumlah angsuran yang dibayar pada periode
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

            // Jasa pinjaman anggota yang sudah dibayar
            $total += $bungaPerAngsuran * $jumlahLunas;
        }

        return round($total);
    }

   public function simpanShu(
    $idAnggota,
    $periodeAwal,
    $periodeAkhir,
    $shuSimpanan,
    $shuPinjaman,
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

                'shu_anggota'   => round($shuAnggota),

                'pajak'         => round($pajak),
            ]

        );
        
    }
}