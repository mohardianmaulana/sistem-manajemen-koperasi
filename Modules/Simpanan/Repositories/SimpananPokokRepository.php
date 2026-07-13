<?php

namespace Modules\Simpanan\Repositories;

use App\Models\Core\User;
use Modules\Simpanan\Entities\SimpananPokok;

class SimpananPokokRepository
{
    public function getAll($idAnggota = null)
    {
        $query = SimpananPokok::with('user');
        if ($idAnggota !== null) {
        $query->where('id_anggota', $idAnggota);
    }

    return $query->paginate(5);
    }
    public function getAllUser()
    {
        return User::orderBy('name')->get();
    }

    public function store(array $data)
    {
        return SimpananPokok::create($data);
    }

    public function findById($id)
    {
        return SimpananPokok::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $simpanan = SimpananPokok::findOrFail($id);

        $simpanan->update($data);

        return $simpanan;
    }
}