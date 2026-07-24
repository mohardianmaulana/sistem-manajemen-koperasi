<?php
namespace Modules\SHU\Services;

use Exception;
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

    public function getSummary()
    {
        return $this->repository->getSummary();
    }

    /**
     * Menyimpan SHU koperasi.
     */
   public function store(array $data)
    {
        $this->validasiPersentase($data);

        $this->hitungNominal($data);

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

        $this->validasiPersentase($data);

        $this->hitungNominal($data);

        return $this->repository->update(
            $shu,
            $data
        );
    }

    private function hitungNominal(array &$data)
    {
        $totalShu = $data['total_shu'];

        $data['jasa_simpanan'] =
            round($totalShu * $data['persen_jasa_simpanan'] / 100);

        $data['jasa_pinjaman'] =
            round($totalShu * $data['persen_jasa_pinjaman'] / 100);

        $data['dana_cadangan'] =
            round($totalShu * $data['persen_dana_cadangan'] / 100);

        $data['jasa_pengurus'] =
            round($totalShu * $data['persen_jasa_pengurus'] / 100);

        $data['dana_sosial'] =
            round($totalShu * $data['persen_dana_sosial'] / 100);
    }

    private function validasiPersentase(array $data)
    {
        $totalPersen =
            $data['persen_jasa_simpanan']
            + $data['persen_jasa_pinjaman']
            + $data['persen_dana_cadangan']
            + $data['persen_jasa_pengurus']
            + $data['persen_dana_sosial'];


        if ($totalPersen != 100) {

            throw new Exception(
                'Total persentase SHU harus tepat 100%.'
            );

        }
    }

}
