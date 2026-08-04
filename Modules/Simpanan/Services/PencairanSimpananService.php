<?php

namespace Modules\Simpanan\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Simpanan\Entities\PencairanSimpanan;
use Modules\Simpanan\Repositories\PencairanSimpananRepository;
use Modules\Simpanan\Repositories\SimpananPokokRepository;
use Modules\Simpanan\Repositories\SimpananWajibRepository;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;

class PencairanSimpananService
{
    protected $repository;
    protected $simpananPokokRepository;
    protected $simpananWajibRepository;
    protected $simpananSukarelaRepository;

    public function __construct(
        PencairanSimpananRepository $repository,
        SimpananPokokRepository $simpananPokokRepository,
        SimpananWajibRepository $simpananWajibRepository,
        SimpananSukarelaRepository $simpananSukarelaRepository
    ) {
        $this->repository = $repository;
        $this->simpananPokokRepository = $simpananPokokRepository;
        $this->simpananWajibRepository = $simpananWajibRepository;
        $this->simpananSukarelaRepository = $simpananSukarelaRepository;
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
        $totalPokok = $this->simpananPokokRepository
            ->totalSimpanan($idAnggota);

        $totalWajib = $this->simpananWajibRepository
            ->totalSimpanan($idAnggota);

        $totalSukarela = $this->simpananSukarelaRepository
            ->totalSimpanan($idAnggota);

        $totalPencairan = $this->repository
            ->totalPencairanAnggota($idAnggota);

        return (
            $totalPokok
            + $totalWajib
            + $totalSukarela
        ) - $totalPencairan;
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $saldo = $this->hitungSaldo(Auth::id());

            if ($data['nominal_pencairan'] > $saldo) {
                throw new \Exception('Nominal pencairan melebihi saldo.');
            }

            $data['kode_pencairan'] = $this->generateKode();
            $data['status'] = PencairanSimpanan::STATUS_PENDING;
            $data['id_anggota'] = Auth::id();

            return $this->repository->store($data);
        });
    }

    public function verifikasi($id)
    {
        return DB::transaction(function () use ($id) {

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

            return $this->repository->update($id, [
                'status' => PencairanSimpanan::STATUS_DITOLAK,
                'id_verifikator' => Auth::id(),
                'tanggal_verifikasi' => Carbon::now(),
                'catatan' => $catatan,
            ]);
        });
    }

    public function cairkan($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            if (
                isset($data['bukti_transfer']) &&
                $data['bukti_transfer']
            ) {

                $data['bukti_transfer'] = $data['bukti_transfer']
                    ->store('bukti-pencairan', 'public');

            }

            return $this->repository->update($id, [
                'status'              => PencairanSimpanan::STATUS_DICAIRKAN,
                'id_bendahara'        => Auth::id(),
                'tanggal_pencairan'   => Carbon::now(),
                'bukti_transfer'      => $data['bukti_transfer'] ?? null,
            ]);
        });
    }

    public function gagal($id, $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {

            return $this->repository->update($id, [
                'status' => PencairanSimpanan::STATUS_GAGAL,
                'id_bendahara' => Auth::id(),
                'catatan' => $catatan,
            ]);
        });
    }


    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $pencairan = $this->repository->getByIdAnggota(
                $id,
                Auth::id()
            );

            if (
                $pencairan->status != PencairanSimpanan::STATUS_PENDING
            ) {
                throw new \Exception(
                    'Pengajuan yang telah diproses tidak dapat diubah.'
                );
            }

            $saldo = $this->hitungSaldo(Auth::id());

            $saldo += $pencairan->nominal_pencairan;

            if ($data['nominal_pencairan'] > $saldo) {
                throw new \Exception(
                    'Nominal pencairan melebihi saldo.'
                );
            }

            return $this->repository->update(
                $pencairan->id,
                [
                    'nominal_pencairan' => $data['nominal_pencairan'],
                    'alasan' => $data['alasan'],
                ]
            );
        });
    }

    public function edit($id)
    {
        return $this->repository->getByIdAnggota(
            $id,
            Auth::id()
        );
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