<?php

namespace Modules\SHU\Repositories;

use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

class ShuKoperasiRepository
{
    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        return ShuKoperasi::paginate(5);
    }

    /**
     * Menyimpan data.
     */
    public function store(array $data)
    {
        return ShuKoperasi::create($data);
    }

    /**
     * Menampilkan detail.
     */
    public function findById($id)
    {
        return ShuKoperasi::findOrFail($id);
    }

    /**
     * Update data.
     */
    public function update($shu, array $data)
    {
        $shu->update($data);

        return $shu;
    }

    /**
     * Total jasa simpanan.
     */
    public function totalJasaSimpanan($tahun)
    {
        $simpananPokok = SimpananPokok::where('status', 'selesai')
            ->whereYear('tanggal', $tahun)
            ->sum('nilai');

        $simpananWajib = SimpananWajib::whereYear('periode', $tahun)
            ->sum('nilai');

        $simpananSukarela = SimpananSukarela::whereYear('periode', $tahun)
            ->sum('nilai');

        return
            $simpananPokok +
            $simpananWajib +
            $simpananSukarela;
    }

    /**
     * Total jasa pinjaman.
     */
    public function totalJasaPinjaman($tahun)
    {
        $total = 0;

        $pinjaman = Pinjaman::with('pengajuan')
        ->whereYear('tanggal_disetujui', $tahun)
        ->get();

        foreach ($pinjaman as $item) {

            $bungaPerAngsuran =
                $item->jumlah_bunga /
                $item->pengajuan->lama_angsuran;

            $jumlahLunas = Angsuran::where('id_pinjaman', $item->id)
                ->where('status_bayar', 'lunas')
                ->whereYear('tanggal_jatuh_tempo', $tahun)
                ->count();

            $total += $bungaPerAngsuran * $jumlahLunas;
        }

        return round($total);
    }
}