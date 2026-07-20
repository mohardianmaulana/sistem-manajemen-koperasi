<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Pembayaran;

class PembayaranRepository {
    public function getAll($fields)
    {
        return Pembayaran::select($fields)
                ->where('status_pembayaran', 'verifikasi')
                ->latest()
                ->get();
    }

    public function getPembayaran($id)
    {
        return Pembayaran::where('id_angsuran', $id)
                ->where('status_pembayaran', 'ditolak')
                ->latest()
                ->first();
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