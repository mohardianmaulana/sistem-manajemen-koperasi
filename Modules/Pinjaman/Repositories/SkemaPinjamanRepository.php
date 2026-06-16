<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\SkemaPinjaman;

class SkemaPinjamanRepository {
    public function getAll($fields)
    {
        return SkemaPinjaman::select($fields)->get();
    }

    public function getById($fields, $id)
    {
        return SkemaPinjaman::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return SkemaPinjaman::create($data);
    }

    public function update($data, $id)
    {
        $skemaPinjaman = SkemaPinjaman::findOrFail($id);
        $skemaPinjaman->update($data);

        return $skemaPinjaman;
    }

    public function delete($id)
    {
        $skemaPinjaman = SkemaPinjaman::findOrFail($id);
        $skemaPinjaman->delete();
    }
}