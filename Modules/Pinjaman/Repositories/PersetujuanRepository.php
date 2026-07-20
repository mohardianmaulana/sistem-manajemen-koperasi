<?php

namespace Modules\Pinjaman\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Pinjaman\Entities\Persetujuan;

class PersetujuanRepository {
    public function getByRole($role)
    {
        return Persetujuan::with('pengajuan')
        ->where('role', $role)
        ->where('status', 'menunggu')
        ->get();
    }

    public function getById($fields, $id)
    {
        return Persetujuan::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Persetujuan::create($data);
    }

    public function update($data, $id)
    {
        $persetujuan = Persetujuan::findOrFail($id);
        $persetujuan->update($data);

        return $persetujuan;
    }
}