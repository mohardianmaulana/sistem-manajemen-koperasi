<?php

namespace Modules\Pinjaman\Services;

use Modules\Pinjaman\Repositories\AngsuranRepository;

class AngsuranService {
    private AngsuranRepository $angsuranRepository;

    public function __construct(AngsuranRepository $angsuranRepository)
    {
        $this->angsuranRepository = $angsuranRepository;
    }

    public function getTagihanBulanIni($fields)
    {
        return $this->angsuranRepository->getTagihanBulanIni($fields);
    }

    public function getAngsuran($id)
    {
        return $this->angsuranRepository->getAngsuran($id);
    }

    public function updateGagalDebet($id)
    {
        $data = ['status_bayar' => 'gagal_debet'];
        return $this->angsuranRepository->update($data, $id);
    }

    public function getVerifikasi($fields)
    {
        return $this->angsuranRepository->getVerifikasi($fields);
    }
}