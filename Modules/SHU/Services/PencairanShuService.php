<?php

namespace Modules\SHU\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Rat\Repositories\RatRepository;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Repositories\PencairanShuRepository;
use Modules\SHU\Repositories\ShuAnggotaRepository;

class PencairanShuService
{
    protected PencairanShuRepository $repository;
    protected ShuAnggotaRepository $shuRepository;
    protected RatRepository $ratRepository;

    public function __construct(
    PencairanShuRepository $repository,
    ShuAnggotaRepository $shuRepository,
    RatRepository $ratRepository
    ) {
        $this->repository = $repository;
        $this->shuRepository = $shuRepository;
        $this->ratRepository = $ratRepository;
    }

    /**
     * Menampilkan data pencairan SHU.
     */
    public function getAll($status = null)
    {
        if (Auth::user()->hasRole('anggota')) {
            return $this->repository->getByAnggota(Auth::id());
        }

        return $this->repository->getAll($status);
    }

    /**
     * Detail pencairan SHU.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Generate data pencairan setelah SHU selesai dihitung.
     */
   public function generatePencairan($idShuAnggota)
    {

        $shu = $this->shuRepository->findById($idShuAnggota);

        if (!$shu) {
            throw new Exception(
                'Data SHU anggota tidak ditemukan.'
            );
        }

        return $this->repository->store([

            'kode_pencairan'    => $this->generateKode(),

            'id_shu_anggota'    => $shu->id,

            'nominal_pencairan' => $shu->shu_anggota,

            'tanggal_pencairan' => now(),

            'status'            => PencairanShu::STATUS_SIAP_DICAIRKAN,

        ]);
    }

    /**
     * Melakukan pencairan SHU.
     */
    public function cairkan($id)
    {
        $pencairan = $this->repository->findById($id);

        if (
            $pencairan->status !==
            PencairanShu::STATUS_SIAP_DICAIRKAN
        ) {
            throw new Exception(
                'Pencairan SHU sudah diproses.'
            );
        }

        return $this->repository->update($id, [

            'status' => PencairanShu::STATUS_DICAIRKAN,

            'tanggal_pencairan' => now(),

            'dicairkan_oleh' => Auth::id(),

        ]);
    }

    /**
     * Menandai pencairan gagal.
     */
    public function gagal($id, $keterangan)
    {
        $pencairan = $this->repository->findById($id);

        if (
            $pencairan->status !==
            PencairanShu::STATUS_SIAP_DICAIRKAN
        ) {
            throw new Exception(
                'Status pencairan tidak dapat diubah.'
            );
        }

        return $this->repository->update($id, [

            'status' => PencairanShu::STATUS_GAGAL,

            'keterangan' => $keterangan,

            'dicairkan_oleh' => Auth::id(),

        ]);
    }

    /**
     * Menghapus data pencairan SHU.
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Dashboard bendahara/pengurus.
     */
    public function getDashboardAdmin()
    {
        return [

            'total_penerima_shu' => $this->shuRepository
                ->totalPenerimaShu(),

            'total_nominal_shu' => $this->shuRepository
                ->totalNominalShu(),

            'total_pencairan' => $this->repository
                ->totalPencairan(),

            'total_nominal_dicairkan' => $this->repository
                ->totalNominalDicairkanSemua(),

            'siap_dicairkan' => $this->repository
                ->totalSiapDicairkan(),

            'dicairkan' => $this->repository
                ->totalDicairkan(),

            'gagal' => $this->repository
                ->totalGagal(),

        ];
    }

    /**
     * Ringkasan pencairan SHU anggota.
     */
    public function getSummaryPencairan($idShuAnggota)
    {
        $shu = $this->shuRepository->findById($idShuAnggota);

        if (!$shu) {
            throw new Exception('Data SHU tidak ditemukan.');
        }

        $totalDicairkan = $this->repository
            ->totalNominalDicairkan($idShuAnggota);

        return [

            'shu' => $shu,

            'total_shu' => $shu->shu_anggota,

            'sudah_dicairkan' => $this->repository
                ->sudahDicairkan($idShuAnggota),

            'total_dicairkan' => $totalDicairkan,

            'sisa_dicairkan' => $shu->shu_anggota - $totalDicairkan,

            'riwayat' => $this->repository
                ->getRiwayatByShuAnggota($idShuAnggota),

        ];
    }

    /**
     * Dashboard anggota.
     */
    public function getDashboardAnggota()
    {
        $shu = $this->shuRepository->getSummary(Auth::id());

        if (!$shu) {
            return [

                'shu_simpanan'      => 0,

                'shu_pinjaman'      => 0,

                'pajak'             => 0,

                'shu_bersih'        => 0,

                'total_dicairkan'   => 0,

                'status_pencairan'  => 'Belum Ada SHU',

                'riwayat'           => collect(),

            ];
        }

        $status = $this->repository
            ->sudahDicairkan($shu->id);

        return [

            'shu_simpanan' => $shu->shu_simpanan,

            'shu_pinjaman' => $shu->shu_pinjaman,

            'pajak' => $shu->pajak,

            'shu_bersih' => $shu->shu_anggota,

            'total_dicairkan' => $this->repository
                ->totalNominalDicairkan($shu->id),

            'status_pencairan' => $status
                ? 'Sudah Dicairkan'
                : 'Belum Dicairkan',

            'riwayat' => $this->repository
                ->getRiwayatByShuAnggota($shu->id),

        ];
    }

    /**
     * Membuat kode pencairan SHU.
     */
    private function generateKode()
    {
        $last = $this->repository->getLastPencairan();

        $nomor = $last ? $last->id + 1 : 1;

        return sprintf(
            'SHU-%s-%04d',
            now()->year,
            $nomor
        );
    }

    public function store(int $tahun)
    {
        return DB::transaction(function () use ($tahun) {

            if (!$this->ratRepository->isRatSelesai()) {
                throw new Exception(
                    'Generate pencairan SHU hanya dapat dilakukan setelah RAT selesai.'
                );
            }

            $shuAnggota = $this->shuRepository->getByTahun($tahun);

            if ($shuAnggota->isEmpty()) {
                throw new Exception(
                    'Data SHU anggota belum tersedia.'
                );
            }

            $nomor = $this->repository->getLastNomor();

            $berhasil = 0;

            foreach ($shuAnggota as $item) {

                if ($this->repository->existsByShuAnggota($item->id)) {
                    continue;
                }

                $nomor++;

                $this->repository->store([

                    'kode_pencairan' => sprintf(
                        'SHU-%s-%04d',
                        $tahun,
                        $nomor
                    ),

                    'id_shu_anggota'    => $item->id,

                    'nominal_pencairan' => $item->shu_anggota,

                    'tanggal_pencairan' => now(),

                    'status'            => PencairanShu::STATUS_SIAP_DICAIRKAN,

                    'keterangan'        => null,

                    'dicairkan_oleh'    => null,

                ]);

                $berhasil++;
            }

            if ($berhasil === 0) {
                throw new Exception(
                    'Seluruh data pencairan SHU tahun tersebut sudah tersedia.'
                );
            }

            return $berhasil;
        });
    }

    public function getListTahun()
    {
        return $this->shuRepository->getTahunList();
    }
}