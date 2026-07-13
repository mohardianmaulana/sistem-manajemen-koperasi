<?php

namespace Modules\Simpanan\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;

class SimpananSukarelaService
{
    protected $repository;

    public function __construct(SimpananSukarelaRepository $repository)
    {
        $this->repository = $repository;
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

            $data['bukti'] = $data['bukti']->store('bukti-simpanan','public');

        }

        /**
         * Update data master
         */
        $this->repository->update($master, [

            'status' => $data['status'],

            'bukti' => $data['bukti'] ?? $master->bukti,

        ]);

        /**
         * Jika disetujui
         */
        if ($master->status == 'selesai') {

            $this->repository->storeSimpanan([

                'nilai' => $master->nilai,

                'periode' => $master->periode,

                'tahun' => $master->tahun,

                'id_anggota' => $master->id_anggota,

            ]);
        }

        return $master;
    }
  
}