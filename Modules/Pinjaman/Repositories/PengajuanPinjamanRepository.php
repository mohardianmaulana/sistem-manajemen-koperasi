<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\PengajuanPinjaman;

class PengajuanPinjamanRepository {
    public function getAll($fields)
    {
        return PengajuanPinjaman::select($fields)->latest()->get();
    }

    public function getById($fields, $id)
    {
        return PengajuanPinjaman::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return PengajuanPinjaman::create($data);
    }

    public function update($data, $id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $pengajuan_pinjaman->update($data);

        return $pengajuan_pinjaman;
    }

    public function delete($id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $pengajuan_pinjaman->delete();
    }
}