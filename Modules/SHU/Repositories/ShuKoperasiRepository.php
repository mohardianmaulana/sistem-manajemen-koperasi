<?php

namespace Modules\SHU\Repositories;

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
        return Pinjaman::where('status_pinjaman', 'selesai')
            ->whereYear('tanggal_disetujui', $tahun)
            ->sum('jumlah_bunga');
    }
}