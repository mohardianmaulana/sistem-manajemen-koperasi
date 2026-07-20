<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\SkemaPinjaman;

class SkemaPinjamanRepository {
    public function getAll($fields)
    {
        return SkemaPinjaman::select($fields)->get();
    }

    public function getAllAktif($fields)
    {
        return SkemaPinjaman::where('status', 'aktif')->select($fields)->get();
    }

    public function getById($fields, $id)
    {
        return SkemaPinjaman::with('daftarJaminan')
                ->select($fields)->findOrFail($id);
    }

    public function getDetail($id)
    {
        return SkemaPinjaman::with('daftarJaminan')
            ->findOrFail($id);
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

    public function syncJaminan($skemaPinjamanId, $jaminanIds)
    {
        $skemaPinjaman = SkemaPinjaman::findOrFail($skemaPinjamanId);

        $skemaPinjaman->daftarJaminan()->sync($jaminanIds);
    }

    public function detachJaminan($id)
    {
        $skemaPinjaman = SkemaPinjaman::findOrFail($id);

        $skemaPinjaman->daftarJaminan()->detach();
    }
}