<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Pembayaran;

class PembayaranRepository {
    public function getAll($fields)
    {
        return Pembayaran::select($fields)->latest()->get();
    }

    public function getById($fields, $id)
    {
        return Pembayaran::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Pembayaran::create($data);
    }

    public function update($data, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update($data);

        return $pembayaran;
    }
}