<?php

namespace Modules\Simpanan\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Simpanan\Entities\PencairanSimpanan;
use Modules\Simpanan\Repositories\PencairanSimpananRepository;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;
use Exception;
use Modules\Rat\Repositories\RatRepository;

class PencairanSimpananService
{
    protected $repository;
    protected $simpananSukarelaRepository;
    protected $ratRepository;

    public function __construct(
    PencairanSimpananRepository $repository,
    SimpananSukarelaRepository $simpananSukarelaRepository,
    RatRepository $ratRepository
    ) {
        $this->repository = $repository;
        $this->simpananSukarelaRepository = $simpananSukarelaRepository;
        $this->ratRepository = $ratRepository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

   public function hitungSaldo($idAnggota)
    {
        $totalSukarela = $this->simpananSukarelaRepository
            ->totalSimpanan($idAnggota);

        $totalPencairan = $this->repository
            ->totalPencairanAnggota($idAnggota);

        return $totalSukarela - $totalPencairan;
    }

    public function store()
    {
        return DB::transaction(function () {

            if (!$this->ratRepository->isRatSelesai()) {
                throw new Exception(
                    'Pengajuan pencairan hanya dapat dilakukan setelah RAT selesai.'
                );
            }

            if ($this->repository->hasPendingRequest(Auth::id())) {
                throw new Exception(
                    'Masih terdapat pengajuan pencairan yang sedang diproses.'
                );
            }

            if ($this->repository->hasPencairanByTahun(Auth::id(), now()->year)) {
                throw new Exception(
                    'Pencairan simpanan untuk tahun ini sudah pernah dilakukan.'
                );
            }

            $saldo = $this->hitungSaldo(Auth::id());

            if ($saldo <= 0) {
                throw new Exception(
                    'Saldo simpanan sukarela tidak tersedia.'
                );
            }

            return $this->repository->store([
                'kode_pencairan' => $this->generateKode(),
                'nominal_pencairan' => $saldo,
                'status' => PencairanSimpanan::STATUS_PENDING,
                'id_anggota' => Auth::id(),
            ]);
        });
    }

    public function verifikasi($id)
    {
        return DB::transaction(function () use ($id) {

            $pencairan = $this->repository->getById($id);

            if (
                $pencairan->status !==
                PencairanSimpanan::STATUS_PENDING
            ) {
                throw new Exception(
                    'Pengajuan pencairan hanya dapat diverifikasi jika masih berstatus pending.'
                );
            }

            return $this->repository->update($id, [
                'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
                'id_verifikator' => Auth::id(),
                'tanggal_verifikasi' => Carbon::now(),
            ]);
        });
    }  

    public function tolak($id, $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {

            $pencairan = $this->repository->getById($id);

            if (
                $pencairan->status !==
                PencairanSimpanan::STATUS_PENDING
            ) {
                throw new Exception(
                    'Pengajuan pencairan hanya dapat ditolak jika masih berstatus pending.'
                );
            }

            return $this->repository->update($id, [
                'status' => PencairanSimpanan::STATUS_DITOLAK,
                'id_verifikator' => Auth::id(),
                'tanggal_verifikasi' => Carbon::now(),
                'catatan' => $catatan,
            ]);
        });
    }

    public function cairkan($id)
    {
        return DB::transaction(function () use ($id) {

            $pencairan = $this->repository->getById($id);

            if (
                $pencairan->status !==
                PencairanSimpanan::STATUS_DIVERIFIKASI
            ) {
                throw new Exception(
                    'Pencairan hanya dapat dilakukan pada pengajuan yang telah diverifikasi.'
                );
            }

            return $this->repository->update($id, [
                'status' => PencairanSimpanan::STATUS_DICAIRKAN,
                'id_bendahara' => Auth::id(),
                'tanggal_pencairan' => Carbon::now(),
            ]);
        });
    }

    public function gagal($id, $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {

            $pencairan = $this->repository->getById($id);

            if (
                $pencairan->status !==
                PencairanSimpanan::STATUS_DIVERIFIKASI
            ) {
                throw new Exception(
                    'Status gagal hanya dapat diberikan pada pengajuan yang telah diverifikasi.'
                );
            }

            return $this->repository->update($id, [
                    'status' => PencairanSimpanan::STATUS_GAGAL,
                'id_bendahara' => Auth::id(),
                'catatan' => $catatan,
            ]);
        });
    }


    
    private function generateKode()
    {
        return 'PCS-' . now()->format('YmdHis');
    }

    public function totalPendingAnggota($idAnggota)
    {
        return $this->repository->totalPendingAnggota($idAnggota);
    }

    public function totalDicairkan($idAnggota)
    {
        return $this->repository->totalDicairkan($idAnggota);
    }

    public function totalPending()
    {
        return $this->repository->totalPending();
    }

    public function totalDiverifikasi()
    {
        return $this->repository->totalDiverifikasi();
    }

    public function totalDitolak()
    {
        return $this->repository->totalDitolak();
    }

    public function totalSiapDicairkan()
    {
        return $this->repository->totalSiapDicairkan();
    }

    public function totalSudahDicairkan()
    {
        return $this->repository->totalSudahDicairkan();
    }

    public function totalGagal()
    {
        return $this->repository->totalGagal();
    }
}