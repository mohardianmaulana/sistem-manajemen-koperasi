<?php

namespace Modules\Pinjaman\Services;

use Modules\Pinjaman\Repositories\JaminanRepository;

class JaminanService {
    private JaminanRepository $jaminanRepository;

    public function __construct(JaminanRepository $jaminanRepository)
    {
        $this->jaminanRepository = $jaminanRepository;
    }

    public function getAll($fields)
    {
        return $this->jaminanRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->jaminanRepository->getById($fields, $id);
    }

    public function create($data)
    {
        $data['status'] = 'aktif';
        return $this->jaminanRepository->create($data);
    }

    public function update($data, $id)
    {
        $data['status'] = 'aktif';
        return $this->jaminanRepository->update($data, $id);
    }

    public function nonaktif($id)
    {
        $data = [
            'status' => 'nonaktif',
        ];
        return $this->jaminanRepository->update($data, $id);
    }

    public function aktif($id)
    {
        $data = [
            'status' => 'aktif',
        ];
        return $this->jaminanRepository->update($data, $id);
    }
}