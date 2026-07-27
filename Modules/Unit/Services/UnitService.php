<?php

namespace Modules\Unit\Services;

use Modules\Unit\Repositories\UnitRepository as RepositoriesUnitRepository;

class UnitService
{
    protected $repository;

    public function __construct(RepositoriesUnitRepository$repository)
    {
        $this->repository = $repository;
    }

    /**
     * Menampilkan seluruh data unit.
     */
   public function getAll($search = null)
    {
        return $this->repository->getAll($search);
    }

    /**
     * Menyimpan data unit.
     */
    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    /**
     * Menampilkan detail unit berdasarkan ID.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Memperbarui data unit.
     */
    public function update(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    /**
     * Menghapus data unit.
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}