<?php

namespace Modules\SHU\Repositories;

use Modules\SHU\Entities\PencairanShu;

class PencairanShuRepository
{
    public function getAll()
    {
        return PencairanShu::with([
                'shuAnggota.user',
                'approver'
            ])
            ->latest()
            ->paginate(10);
    }

    public function getByAnggota($idAnggota)
    {
        return PencairanShu::with('shuAnggota')
            ->whereHas('shuAnggota', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })
            ->latest()
            ->paginate(10);
    }

    public function getPending()
    {
        return PencairanShu::where('status', 'menunggu')
            ->with('shuAnggota.user')
            ->latest()
            ->get();
    }

    public function getById($id)
    {
        return PencairanShu::with([
                'shuAnggota.user',
                'approver'
            ])
            ->findOrFail($id);
    }

    public function store(array $data)
    {
        return PencairanShu::create($data);
    }

    public function approve($id, array $data)
    {
        return PencairanShu::findOrFail($id)
            ->update($data);
    }

    public function reject($id, array $data)
    {
        return PencairanShu::findOrFail($id)
            ->update($data);
    }

    public function cairkan($id, array $data)
    {
        return PencairanShu::findOrFail($id)
            ->update($data);
    }

    public function delete($id)
    {
        return PencairanShu::destroy($id);
    }
}