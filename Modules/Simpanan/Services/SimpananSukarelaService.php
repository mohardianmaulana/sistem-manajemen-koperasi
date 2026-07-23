<?php

namespace Modules\Simpanan\Services;

use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Carbon;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;
use Modules\Simpanan\Services\MasterJenisSimpananService as ServicesMasterJenisSimpananService;

class SimpananSukarelaService
{
    protected $repository;
    protected $masterJenisSimpananService;

    public function __construct(
        SimpananSukarelaRepository $repository,
        ServicesMasterJenisSimpananService $masterJenisSimpananService
    ) {
        $this->repository = $repository;
        $this->masterJenisSimpananService = $masterJenisSimpananService;
    }

    /**
     * LIST DATA
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
    /**
     * CREATE
     */
    public function store(array $data)
    {
        $this->masterJenisSimpananService
         ->cekJadwalAktif('Simpanan Sukarela');

        $data['status'] = 'pending';

        $data['tahun'] = date('Y');

        $data['id_anggota'] = Auth::id();

        return $this->repository->store($data);
    }

    /**
     * FIND BY ID
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * UPDATE
     */
    public function update($id, array $data)
    {
        $master = $this->repository->findById($id);

        /**
         * Upload bukti
         */
        if (isset($data['bukti']) && $data['bukti']) {
            $data['bukti'] = $data['bukti']->store('bukti-simpanan', 'public');
        }

        /**
         * Jika anggota
         */
        if (Auth::user()->hasRole('anggota')) {

            $this->masterJenisSimpananService
                ->cekJadwalAktif('Simpanan Sukarela');

            if ($master->status != 'tidak berhasil') {
                throw new Exception(
                    'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
                );
            }

            $this->repository->update($master, [
                'bukti' => $data['bukti'] ?? $master->bukti,
            ]);

            return $master;
        }

        /**
         * Jika admin
         */
        $this->repository->update($master, [

            'status' => $data['status'],

            'bukti' => $data['bukti'] ?? $master->bukti,

        ]);

        /**
         * Jika status selesai
         */
        if ($data['status'] == 'selesai') {

            // Sebaiknya cek dulu apakah sudah pernah masuk tabel final
            if (!$this->repository->sudahMasukSimpanan($master)) {

                $this->repository->storeSimpanan([

                    'nilai'      => $master->nilai,

                    'periode'    => $master->periode,

                    'tahun'      => $master->tahun,

                    'id_anggota' => $master->id_anggota,

                ]);

            }
        }

        return $master;
    }

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

    public function generatePeriode($jadwal)
    {
        $masters = $this->repository->getMasterSiapGenerate();

        foreach ($masters as $master) {

            if ($this->repository->sudahAdaPeriode(
                $master->id_anggota,
                $jadwal->tanggal_mulai
            )) {
                continue;
            }

            $this->repository->createPeriode([
                'id_anggota' => $master->id_anggota,
                'nilai'      => $master->nilai,
                'periode'    => $jadwal->tanggal_mulai,
                'tahun'      => Carbon::parse($jadwal->tanggal_mulai)->year,
            ]);
        }
    }
}