<?php

namespace Modules\Pinjaman\Services;

use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Repositories\PinjamanRepository;

class PinjamanService {
    private PinjamanRepository $pinjamanRepository;

    public function __construct(PinjamanRepository $pinjamanRepository)
    {
        $this->pinjamanRepository = $pinjamanRepository;
    }

    public function getAll($fields, $status_pinjaman = null, $id_skema_pinjaman = null)
    {
        $pinjaman = Pinjaman::select($fields)->with('pengajuan.skemaPinjaman');

        if ($status_pinjaman) {
            $pinjaman->where('status_pinjaman', $status_pinjaman);
        }

        // FILTER SKEMA
        if ($id_skema_pinjaman) {
            $pinjaman->whereHas('pengajuan', function ($q) use ($id_skema_pinjaman) {
                $q->where('id_skema_pinjaman', $id_skema_pinjaman);
            });
        }

        return $pinjaman->get();
    }

    public function getById($fields, $id)
    {
        return $this->pinjamanRepository->getById($fields, $id);
    }

    public function cekPinjamanAktif($user_id)
    {
        return $this->pinjamanRepository->cekPinjamanAktif($user_id);
    }

    // public function create($data)
    // {
    //     return $this->pinjamanRepository->create($data);
    // }

    // public function update($data, $id)
    // {
    //     return $this->pinjamanRepository->update($data, $id);
    // }
}