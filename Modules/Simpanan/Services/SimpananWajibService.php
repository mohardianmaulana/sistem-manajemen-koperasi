<?php

namespace Modules\Simpanan\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Simpanan\Repositories\SimpananWajibRepository;

class SimpananWajibService
{
    protected $repository;

    public function __construct(SimpananWajibRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generate otomatis periode bulan berjalan.
     */
    public function autoGeneratePeriode()
    {
        DB::transaction(function () {

            $periode = now()->startOfMonth();

            if ($this->repository->periodeExists(
                $periode->month,
                $periode->year
            )) {
                return;
            }

            $lastPeriode = $this->repository->getLastPeriode();

            if (!$lastPeriode) {
                return;
            }

            foreach ($this->repository->getAllAnggota() as $anggota) {

                $this->repository->store([
                    'nilai'      => $lastPeriode->nilai,
                    'periode'    => $periode,
                    'tahun'      => $periode->year,
                    'status'     => 'pending',
                    'id_anggota' => $anggota->id,
                ]);
            }
        });
    }

    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        $idAnggota = Auth::user()->hasRole('admin')
            ? null
            : Auth::id();

        return $this->repository->getAll(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }

    /**
     * Ringkasan.
     */
    public function getSummary()
    {
        $idAnggota = Auth::user()->hasRole('admin')
            ? null
            : Auth::id();

        return $this->repository->getSummary(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }

    public function store(array $data)
    {
        if ($this->repository->periodeExists(
            date('m', strtotime($data['periode'])),
            date('Y', strtotime($data['periode']))
        )) {
            throw new Exception('Periode tersebut sudah tersedia.');
        }

        foreach ($this->repository->getAllAnggota() as $anggota) {

            $this->repository->store([
                'nilai'      => $data['nilai'],
                'periode'    => $data['periode'],
                'tahun'      => date('Y', strtotime($data['periode'])),
                'status'     => 'pending',
                'id_anggota' => $anggota->id,
            ]);

        }
    }
    /**
     * Detail.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Update.
     */
    public function update($id, array $data)
    {
        $master = $this->repository->findById($id);

        if (isset($data['bukti']) && $data['bukti']) {
            $data['bukti'] = $data['bukti']->store(
                'bukti-simpanan',
                'public'
            );
        }

        if (Auth::user()->hasRole('anggota')) {

            if ($master->status != 'tidak berhasil') {
                throw new Exception(
                    'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
                );
            }

            return $this->repository->update($master, [
                'bukti' => $data['bukti'] ?? $master->bukti,
            ]);
        }

        $this->repository->update($master, [
            'status' => $data['status'],
            'bukti'  => $data['bukti'] ?? $master->bukti,
        ]);

        if (
            $data['status'] == 'selesai' &&
            !$this->repository->existsSimpanan(
                $master->id_anggota,
                $master->periode
            )
        ) {
            $this->repository->storeSimpanan([
                'nilai'      => $master->nilai,
                'periode'    => $master->periode,
                'tahun'      => $master->tahun,
                'id_anggota' => $master->id_anggota,
            ]);
        }

        return $master;
    }

    /**
     * Export autodebit.
     */
    public function exportAutoDebit()
    {
        return $this->repository->exportAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }

    /**
     * Total autodebit.
     */
    public function totalAutoDebit()
    {
        return $this->repository->totalAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }
}