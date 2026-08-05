<?php

namespace Modules\Rat\Repositories;

use Modules\Rat\Entities\Rat;

class RatRepository
{
    public function getAll()
    {
        return Rat::latest('tahun')
            ->paginate(10);
    }

    public function getById($id)
    {
        return Rat::findOrFail($id);
    }

    public function getByTahun($tahun)
    {
        return Rat::where('tahun', $tahun)
            ->first();
    }

    public function getRatSelesai()
    {
        return Rat::where(
                'status',
                Rat::STATUS_SELESAI
            )
            ->latest('tahun')
            ->first();
    }

    public function store(array $data)
    {
        return Rat::create($data);
    }

    public function update($id, array $data)
    {
        $rat = $this->getById($id);

        $rat->update($data);

        return $rat;
    }

    public function delete($id)
    {
        return $this->getById($id)->delete();
    }

    public function isRatSelesai()
    {
        $rat = Rat::where('tahun', now()->year)
            ->first();

        if (!$rat) {
            return false;
        }

        return $rat->status === Rat::STATUS_SELESAI;
    }

    public function existsByTahun($tahun)
    {
        return Rat::where('tahun', $tahun)
            ->exists();
    }
}