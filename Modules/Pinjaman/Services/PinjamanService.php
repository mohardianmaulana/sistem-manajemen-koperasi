<?php

namespace Modules\Pinjaman\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Repositories\PinjamanRepository;

class PinjamanService {
    private PinjamanRepository $pinjamanRepository;

    public function __construct(PinjamanRepository $pinjamanRepository)
    {
        $this->pinjamanRepository = $pinjamanRepository;
    }

    public function monitoring($status = null, $skema = null)
    {
        return $this->pinjamanRepository->monitoring($status, $skema);
    }

    public function getById($fields, $id)
    {
        return $this->pinjamanRepository->getById($fields, $id);
    }

    public function cekPinjamanAktif($user_id)
    {
        return $this->pinjamanRepository->cekPinjamanAktif($user_id);
    }

    public function getByAnggota($fields)
    {
        $user = Auth::id();
        return $this->pinjamanRepository->getByAnggota($fields, $user);
    }
}