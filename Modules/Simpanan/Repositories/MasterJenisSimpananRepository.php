<?php

namespace Modules\Simpanan\Repositories;

use Modules\Simpanan\Entities\MasterJenisSimpanan;

class MasterJenisSimpananRepository
{
    public function getAll()
    {
        return MasterJenisSimpanan::all();
    }

    public function store(array $data)
    {
        return MasterJenisSimpanan::create($data);
    }

    public function update($id, array $data)
    {
        $master = MasterJenisSimpanan::findOrFail($id);

        $master->update($data);

        return $master;
    }

    public function findById($id)
    {
        return MasterJenisSimpanan::findOrFail($id);
    }

    public function findByJenis($jenis)
    {
        return MasterJenisSimpanan::where(
            'nama_jenis_simpanan',
            $jenis
        )->first();
    }
}