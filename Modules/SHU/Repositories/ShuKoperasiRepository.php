<?php

namespace Modules\SHU\Repositories;

use Modules\SHU\Entities\ShuKoperasi;

class ShuKoperasiRepository
{
    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        return ShuKoperasi::paginate(5);
    }

    /**
     * Menyimpan data.
     */
    public function store(array $data)
    {
        return ShuKoperasi::create($data);
    }

    /**
     * Menampilkan detail.
     */
    public function findById($id)
    {
        return ShuKoperasi::findOrFail($id);
    }

    /**
     * Update data.
     */
    public function update($shu, array $data)
    {
        $shu->update($data);

        return $shu;
    }
   
}