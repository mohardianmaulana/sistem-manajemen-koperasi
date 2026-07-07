<?php
namespace Modules\SHU\Services;

use App\Models\Core\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\SHU\Entities\ShuAnggota;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;

    class ShuAnggotaService
    {
        public function getAll(){
            
            return ShuAnggota::with('user')->paginate(5);
        }

         public function hitungSemuaAnggota($tahun)
        {
            DB::beginTransaction();

            try {

                /**
                 * Ambil data SHU berdasarkan tahun
                 */
                $shu = ShuKoperasi::where('tahun', $tahun)->first();

                if (!$shu) {
                    throw new \Exception("Data SHU tahun {$tahun} tidak ditemukan.");
                }

                /**
                 * Total simpanan seluruh anggota
                 */
                $totalSimpanan = $this->totalSimpananSemua($tahun);

                /**
                 * Total angsuran seluruh anggota
                 */
                $totalAngsuran = $this->totalAngsuranSemua($tahun);

                /**
                 * Ambil seluruh anggota
                 */
                $user = User::all();

                foreach ($user as $user) {

                    /**
                     * Total simpanan anggota
                     */
                    $simpananAnggota = $this->totalSimpananAnggota(
                        $user->id,
                        $tahun
                    );

                    /**
                     * Total angsuran anggota
                     */
                    $angsuranAnggota = $this->totalAngsuranAnggota(
                        $user->id,
                        $tahun
                    );

                    if ($totalSimpanan == 0) {
                        throw new \Exception(
                            "Perhitungan SHU tidak dapat dilakukan karena belum terdapat data simpanan pada tahun {$tahun}."
                        );
                    }

                    /**
                     * Hitung SHU Simpanan
                     */
                    $shuSimpanan = $this->hitungShuSimpanan(
                        $simpananAnggota,
                        $totalSimpanan,
                        $shu->jasa_simpanan
                    );

                    /**
                     * Hitung SHU Pinjaman
                     */
                    $shuPinjaman = $this->hitungShuPinjaman(
                        $angsuranAnggota,
                        $totalAngsuran,
                        $shu->jasa_pinjaman
                    );

                    /**
                     * Simpan hasil SHU
                     */
                    $this->simpanShu(
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


    private function totalSimpananSemua($tahun)
    {
        $tabungan = SimpananPokok::where('status', 'selesai')
            ->whereYear('tanggal', $tahun)
            ->sum('nilai');

        $simpananWajib = SimpananWajib::whereYear('periode', $tahun)
            ->sum('nilai');

        $simpananSukarela = SimpananSukarela::whereYear('periode', $tahun)
            ->sum('nilai');

        return
            $tabungan +
            $simpananWajib +
            $simpananSukarela;
    }

    private function totalSimpananAnggota($idAnggota, $tahun)
    {
        $tabungan = SimpananPokok::where('id_anggota', $idAnggota)
            ->where('status', 'selesai')
            ->whereYear('tanggal', $tahun)
            ->sum('nilai');

        $simpananWajib = SimpananWajib::where('id_anggota', $idAnggota)
            ->whereYear('periode', $tahun)
            ->sum('nilai');

        $simpananSukarela = SimpananSukarela::where('id_anggota', $idAnggota)
            ->whereYear('periode', $tahun)
            ->sum('nilai');

        return
            $tabungan +
            $simpananWajib +
            $simpananSukarela;
    }

    private function totalAngsuranSemua($tahun)
    {
        return Angsuran::join(
                'pinjaman',
                'pinjaman.id',
                '=',
                'angsuran.id_pinjaman'
            )
            ->where('angsuran.status_bayar', 'lunas')
            ->whereYear(
                'angsuran.tanggal_jatuh_tempo',
                $tahun
            )
            ->sum('angsuran.jumlah_angsuran');
    }

    /**
     * Menghitung total angsuran anggota tertentu.
     *
     * Relasi:
     * anggota
     * -> pengajuan_pinjaman
     * -> pinjaman
     * -> angsuran
     *
     * @param int $idAnggota
     * @param int $tahun
     * @return int
     */
    private function totalAngsuranAnggota(
        $idAnggota,
        $tahun
    ) {
        return Angsuran::join(
                'pinjaman',
                'pinjaman.id',
                '=',
                'angsuran.id_pinjaman'
            )
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
            ->where(
                'angsuran.status_bayar',
                'lunas'
            )
            ->whereYear(
                'angsuran.tanggal_jatuh_tempo',
                $tahun
            )
            ->sum('angsuran.jumlah_angsuran');
    }

    /**
     * Menghitung SHU dari jasa simpanan.
     *
     * Rumus:
     *
     * Simpanan Anggota
     * --------------------- x SHU Jasa Simpanan
     * Total Simpanan
     *
     * @param int $simpananAnggota
     * @param int $totalSimpanan
     * @param int $shuJasaSimpanan
     * @return int
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
     * Menghitung SHU dari jasa pinjaman.
     *
     * Rumus:
     *
     * Angsuran Anggota
     * --------------------- x SHU Jasa Pinjaman
     * Total Angsuran
     *
     * @param int $angsuranAnggota
     * @param int $totalAngsuran
     * @param int $shuJasaPinjaman
     * @return int
     */
    private function hitungShuPinjaman(
        $angsuranAnggota,
        $totalAngsuran,
        $shuJasaPinjaman
    ) {

        if ($totalAngsuran <= 0) {
            return 0;
        }

        return round(
            ($angsuranAnggota / $totalAngsuran)
            * $shuJasaPinjaman
        );
    }

    /**
     * Menyimpan hasil SHU anggota.
     *
     * Jika sudah ada data tahun yang sama,
     * maka akan diupdate.
     *
     * @param int $idAnggota
     * @param int $tahun
     * @param int $shuSimpanan
     * @param int $shuPinjaman
     * @return void
     */
    private function simpanShu(
        $idAnggota,
        $tahun,
        $shuSimpanan,
        $shuPinjaman
    ) {

        ShuAnggota::updateOrCreate(

            [
                'id_anggota' => $idAnggota,
                'tahun'      => $tahun,
            ],

            [
                'shu_simpanan' => $shuSimpanan,
                'shu_pinjaman' => $shuPinjaman,
                'shu_anggota'  => (
                    $shuSimpanan +
                    $shuPinjaman
                ),
                'tanggal' => now(),
            ]
        );
    }

}