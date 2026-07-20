<?php

namespace Modules\Pinjaman\Services;

use Exception;
use Illuminate\Support\Facades\DB;
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

    public function getAllAktif($fields)
    {
        return $this->skemaPinjamanRepository->getAllAktif($fields);
    }

    public function getById($fields, $id)
    {
        return $this->skemaPinjamanRepository->getById($fields, $id);
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            $jaminanIds = $data['jaminan_ids'] ?? [];
            unset($data['jaminan_ids']);
            $skemaPinjaman = $this->skemaPinjamanRepository->create($data);
            if (
                $skemaPinjaman->jaminan === 'ada'
                && !empty($jaminanIds)
            ) {
                $this->skemaPinjamanRepository
                ->syncJaminan($skemaPinjaman->id, $jaminanIds);
            }
            DB::commit();
            return $skemaPinjaman;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            $jaminanIds = $data['jaminan_ids'] ?? [];

            unset($data['jaminan_ids']);

            $skemaPinjaman = $this->skemaPinjamanRepository->update($data, $id);

            if ($skemaPinjaman->jaminan === 'ada') {
            $this->skemaPinjamanRepository
                ->syncJaminan($skemaPinjaman->id, $jaminanIds);
            } else {
                $this->skemaPinjamanRepository
                    ->syncJaminan($skemaPinjaman->id, []);
            }

            DB::commit();

            return $skemaPinjaman;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function nonaktif($id)
    {
        $data = [
            'status' => 'nonaktif',
        ];
        return $this->skemaPinjamanRepository->update($data, $id);
    }

    public function aktif($id)
    {
        $data = [
            'status' => 'aktif',
        ];
        return $this->skemaPinjamanRepository->update($data, $id);
    }
}