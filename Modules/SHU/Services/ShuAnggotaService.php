<?php
namespace Modules\SHU\Services;


use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SHU\Repositories\ShuAnggotaRepository;


    class ShuAnggotaService
    {
        protected $repository;

    public function __construct(ShuAnggotaRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        if (Auth::user()->hasRole('admin')) {
        return $this->repository->getAll();
        }

        return $this->repository->getAll();
    }

    public function hitungSemuaAnggota($tahun)
    {
        DB::beginTransaction();

        try {

            $shu = $this->repository->getShuByTahun($tahun);

            if (!$shu) {
                throw new Exception(
                    "Data SHU tahun {$tahun} tidak ditemukan."
                );
            }

            $totalSimpanan = $this->repository
                ->totalSimpananSemua($tahun);

            $totalJasaPinjaman = $this->repository
                ->totalJasaPinjamanSemua($tahun);

            if ($totalSimpanan <= 0 && $totalJasaPinjaman <= 0) {

                throw new Exception(
                    "Perhitungan SHU tidak dapat dilakukan karena belum terdapat transaksi."
                );

            }

            $users = $this->repository->getUsers();

            foreach ($users as $user) {

                $simpananAnggota = $this->repository
                    ->totalSimpananAnggota(
                        $user->id,
                        $tahun
                    );

                $jasaPinjamanAnggota = $this->repository
                    ->totalJasaPinjamanAnggota(
                        $user->id,
                        $tahun
                    );

                $shuSimpanan = $this->hitungShuSimpanan(
                    $simpananAnggota,
                    $totalSimpanan,
                    $shu->jasa_simpanan
                );

                $shuPinjaman = $this->hitungShuPinjaman(
                    $jasaPinjamanAnggota,
                    $totalJasaPinjaman,
                    $shu->jasa_pinjaman
                );

                $this->repository->simpanShu(
                    $user->id,
                    $tahun,
                    $shuSimpanan,
                    $shuPinjaman
                );
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Business Logic
     */
    private function hitungShuSimpanan(
        $simpananAnggota,
        $totalSimpanan,
        $shuJasaSimpanan
    ) {
        if ($totalSimpanan <= 0) {
            return 0;
        }

        return round(
            ($simpananAnggota / $totalSimpanan)
            * $shuJasaSimpanan
        );
    }

    /**
     * Business Logic
     */
    private function hitungShuPinjaman(
        $jasaPinjamanAnggota,
        $totalJasaPinjaman,
        $shuJasaPinjaman
    ) {
        if ($totalJasaPinjaman <= 0) {
            return 0;
        }

        return round(
            ($jasaPinjamanAnggota / $totalJasaPinjaman)
            * $shuJasaPinjaman
        );
    }

}