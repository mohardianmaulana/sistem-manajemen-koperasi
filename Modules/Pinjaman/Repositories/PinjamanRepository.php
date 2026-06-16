<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Pinjaman;

class PinjamanRepository {
    public function getAll($fields)
    {
        return Pinjaman::select($fields)->latest()->get();
    }

    public function getById($fields, $id)
    {
        return Pinjaman::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Pinjaman::create($data);
    }

    public function update($data, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        return $pinjaman->update($data);
    }

    public function cekPinjamanAktif($user_id)
    {
        return Pinjaman::whereHas('pengajuan', function ($query) use ($user_id) {
            $query->where('id_anggota', $user_id);
        })
        ->where('status_pinjaman', '!=', 'selesai')
        ->exists(); // hanya cek ada atau tidak (true/false)
    }
}