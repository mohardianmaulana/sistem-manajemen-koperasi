<?php
namespace Modules\SHU\Services;

use Modules\Pinjaman\Entities\Pinjaman;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

class ShuKoperasiService
{
    public function getAll()
    {
        return ShuKoperasi::paginate(5);
    }

     public function store(array $data)
    {
        $data['jasa_simpanan'] = $this->totalJasaSimpanan($data['tahun']);

        $data['jasa_pinjaman'] = $this->totalJasaPinjaman($data['tahun']);

        $data['total_shu'] = $this->hitungTotalShu(
            $data['jasa_simpanan'],
            $data['jasa_pinjaman'],
            $data['dana_cadangan'],
            $data['jasa_pengurus'],
            $data['dana_sosial']
        );

        return ShuKoperasi::create($data);
    }

    /**
     * Menampilkan satu data
     */
    public function findById($id)
    {
        return ShuKoperasi::findOrFail($id);
    }

    /**
     * Update SHU koperasi
     *
     * Yang boleh diubah hanya:
     * - dana_cadangan
     * - jasa_pengurus
     * - dana_sosial
     */
    public function update($id, array $data)
    {
        $shu = ShuKoperasi::findOrFail($id);

        $dataUpdate = [

            'dana_cadangan' => $data['dana_cadangan'],

            'jasa_pengurus' => $data['jasa_pengurus'],

            'dana_sosial' => $data['dana_sosial'],

        ];

        /**
         * Hitung ulang otomatis
         */
        $dataUpdate['jasa_simpanan'] = $this->totalJasaSimpanan($shu->tahun);

        $dataUpdate['jasa_pinjaman'] = $this->totalJasaPinjaman($shu->tahun);

        $dataUpdate['total_shu'] = $this->hitungTotalShu(

            $dataUpdate['jasa_simpanan'],

            $dataUpdate['jasa_pinjaman'],

            $dataUpdate['dana_cadangan'],

            $dataUpdate['jasa_pengurus'],

            $dataUpdate['dana_sosial']

        );

        $shu->update($dataUpdate);

        return $shu;
    }
    
    public function getDataCreate($tahun)
    {
        return [

            'jasaSimpanan' => $this->totalJasaSimpanan($tahun),

            'jasaPinjaman' => $this->totalJasaPinjaman($tahun),

        ];
    }

    /**
     * Total jasa simpanan
     */
    private function totalJasaSimpanan($tahun)
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
     * Total jasa pinjaman
     */
    private function totalJasaPinjaman($tahun)
    {
        return Pinjaman::whereYear('tanggal_disetujui', $tahun)
            ->sum('jumlah_bunga');
    }

    /**
     * Menghitung total SHU
     */
    private function hitungTotalShu(
        $jasaSimpanan,
        $jasaPinjaman,
        $danaCadangan,
        $jasaPengurus,
        $danaSosial
    ) {
        return
            $jasaSimpanan +
            $jasaPinjaman +
            $danaCadangan +
            $jasaPengurus +
            $danaSosial;
    }
}
