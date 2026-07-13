<?php
namespace Modules\SHU\Services;


use Modules\SHU\Repositories\ShuKoperasiRepository;

class ShuKoperasiService
{
     protected $repository;

    public function __construct(
        ShuKoperasiRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Menyimpan SHU koperasi.
     */
    public function store(array $data)
    {
        $data['jasa_simpanan'] = $this->repository
            ->totalJasaSimpanan($data['tahun']);

        $data['jasa_pinjaman'] = $this->repository
            ->totalJasaPinjaman($data['tahun']);

        $data['total_shu'] = $this->hitungTotalShu(

            $data['jasa_simpanan'],

            $data['jasa_pinjaman'],

            $data['dana_cadangan'],

            $data['jasa_pengurus'],

            $data['dana_sosial']

        );

        return $this->repository->store($data);
    }

    /**
     * Detail data.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Update SHU koperasi.
     */
    public function update($id, array $data)
    {
        $shu = $this->repository->findById($id);

        $dataUpdate = [

            'dana_cadangan' => $data['dana_cadangan'],

            'jasa_pengurus' => $data['jasa_pengurus'],

            'dana_sosial' => $data['dana_sosial'],

        ];

        /**
         * Hitung ulang otomatis.
         */
        $dataUpdate['jasa_simpanan'] = $this->repository
            ->totalJasaSimpanan($shu->tahun);

        $dataUpdate['jasa_pinjaman'] = $this->repository
            ->totalJasaPinjaman($shu->tahun);

        $dataUpdate['total_shu'] = $this->hitungTotalShu(

            $dataUpdate['jasa_simpanan'],

            $dataUpdate['jasa_pinjaman'],

            $dataUpdate['dana_cadangan'],

            $dataUpdate['jasa_pengurus'],

            $dataUpdate['dana_sosial']

        );

        return $this->repository->update(
            $shu,
            $dataUpdate
        );
    }

    /**
     * Data yang dibutuhkan halaman create.
     */
    public function getDataCreate($tahun)
    {
        return [

            'jasaSimpanan' => $this->repository
                ->totalJasaSimpanan($tahun),

            'jasaPinjaman' => $this->repository
                ->totalJasaPinjaman($tahun),

        ];
    }

    /**
     * Business Logic
     * Menghitung total SHU.
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
