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

    public function hitungSemuaAnggota(
    $periodeAwal,
    $periodeAkhir,
    $persenJasaPengurus,
    $persenPajak
    ) {
        DB::beginTransaction();

        try {

            /**
             * Mengambil data SHU koperasi
             */
            $shu = $this->repository->getShuKoperasi(
                $periodeAwal,
                $periodeAkhir
            );

            if (!$shu) {

                throw new Exception(
                    "Data SHU koperasi pada periode tersebut belum tersedia."
                );

            }

            /**
             * Mengambil total simpanan seluruh anggota
             */
            $totalSimpanan = $this->repository
                ->totalSimpananSemua(
                    $periodeAwal,
                    $periodeAkhir
                );

            /**
             * Mengambil total jasa pinjaman seluruh anggota
             */
            $totalJasaPinjaman = $this->repository
                ->totalJasaPinjamanSemua(
                    $periodeAwal,
                    $periodeAkhir
                );

            /**
             * Validasi transaksi
             */
            if ($totalSimpanan <= 0 && $totalJasaPinjaman <= 0) {

                throw new Exception(
                    "Perhitungan SHU tidak dapat dilakukan karena belum terdapat transaksi."
                );

            }

            /**
             * Mengambil seluruh anggota
             */
            $users = $this->repository->getUsers();

            foreach ($users as $user) {

                /**
                 * Total simpanan anggota
                 */
                $simpananAnggota = $this->repository
                    ->totalSimpananAnggota(
                        $user->id,
                        $periodeAwal,
                        $periodeAkhir
                    );

                /**
                 * Total jasa pinjaman anggota
                 */
                $jasaPinjamanAnggota = $this->repository
                    ->totalJasaPinjamanAnggota(
                        $user->id,
                        $periodeAwal,
                        $periodeAkhir
                    );

                /**
                 * Menghitung SHU Simpanan
                 */
                $shuSimpanan = $this->hitungShuSimpanan(
                    $simpananAnggota,
                    $totalSimpanan,
                    $shu->jasa_simpanan
                );

                /**
                 * Menghitung SHU Pinjaman
                 */
                $shuPinjaman = $this->hitungShuPinjaman(
                    $jasaPinjamanAnggota,
                    $totalJasaPinjaman,
                    $shu->jasa_pinjaman
                );

                /**
                 * Menghitung Jasa Pengurus
                 */
                $jasaPengurus = $this->hitungJasaPengurus(

                    $shu->jasa_pengurus,

                    $persenJasaPengurus,

                    $users->count()

                );

                /**
                 * Menghitung Total SHU sebelum pajak
                 */
                $totalShu = $this->hitungTotalShu(

                    $shuSimpanan,

                    $shuPinjaman,

                    $jasaPengurus

                );

                /**
                 * Menghitung Pajak
                 */
                $pajak = $this->hitungPajak(

                    $totalShu,

                    $persenPajak

                );

                /**
                 * Menghitung SHU Anggota
                 */
                $shuAnggota = $this->hitungShuAnggota(

                    $totalShu,

                    $pajak

                );


                /**
                 * Menyimpan hasil perhitungan SHU
                 */
                $this->repository->simpanShu(

                    $user->id,

                    $periodeAwal,

                    $periodeAkhir,

                    $shuSimpanan,

                    $shuPinjaman,

                    $jasaPengurus,

                    $shuAnggota,

                    $pajak

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

    private function hitungPajak(
    $totalShu,
    $persenPajak
    )
    {
        return round(

            $totalShu *
            $persenPajak /
            100

        );
    }

    private function hitungTotalShu(
    $shuSimpanan,
    $shuPinjaman,
    $jasaPengurus
    ) {
        return round(

            $shuSimpanan +
            $shuPinjaman +
            $jasaPengurus

        );
    }
    private function hitungShuAnggota(
    $totalShu,
    $pajak
    ) {
        return round(

            $totalShu -
            $pajak

        );
    }

    private function hitungJasaPengurus(
    $nominalJasaPengurus,
    $persenJasaPengurus,
    $jumlahAnggota
    ) {
        if ($jumlahAnggota <= 0) {
            return 0;
        }

        $nominalDibagikan = round(
            $nominalJasaPengurus *
            $persenJasaPengurus / 100
        );

        return round(
            $nominalDibagikan /
            $jumlahAnggota
        );
    }
}