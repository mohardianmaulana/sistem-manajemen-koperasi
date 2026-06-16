<?php

namespace Modules\Pinjaman\Services;

use Modules\Pinjaman\Repositories\SkemaPinjamanRepository;

class SkemaPinjamanService {

    private SkemaPinjamanRepository $skemaPinjamanRepository;

    public function __construct(SkemaPinjamanRepository $skemaPinjamanRepository)
    {
        $this->skemaPinjamanRepository = $skemaPinjamanRepository;
    }

    public function getAll($fields)
    {
        return $this->skemaPinjamanRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->skemaPinjamanRepository->getById($fields, $id);
    }

    public function create($data)
    {
        return $this->skemaPinjamanRepository->create($data);
    }

    public function update($data, $id)
    {
        return $this->skemaPinjamanRepository->update($data, $id);
    }

    public function delete($id)
    {
        $this->skemaPinjamanRepository->delete($id);
    }
}