<?php

namespace Modules\Simpanan\Services;

use Illuminate\Support\Facades\Auth;
use Exception;
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
        if (Auth::user()->hasRole('admin')) {
        return $this->repository->getAll();
        }

        return $this->repository->getAll(Auth::id());
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
}