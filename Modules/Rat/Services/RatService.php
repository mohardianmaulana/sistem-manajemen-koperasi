<?php

namespace Modules\Rat\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Rat\Repositories\RatRepository;

class RatService
{
    protected $repository;

    public function __construct(RatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            if ($this->repository->existsByTahun($data['tahun'])) {
                throw new Exception(
                    'Data RAT pada tahun tersebut sudah tersedia.'
                );
            }

            return $this->repository->store($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $rat = $this->repository->getById($id);

            if (
                $rat->tahun != $data['tahun'] &&
                $this->repository->existsByTahun($data['tahun'])
            ) {
                throw new Exception(
                    'Data RAT pada tahun tersebut sudah tersedia.'
                );
            }

            return $this->repository->update($id, $data);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {

            return $this->repository->delete($id);

        });
    }

    public function isRatSelesai()
    {
        return $this->repository->isRatSelesai();
    }
}