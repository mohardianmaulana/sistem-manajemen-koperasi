<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Jaminan;

class JaminanRepository {

    public function getAll($fields)
    {
        return Jaminan::select($fields)->latest()->get();
    }

    public function getById($fields, $id)
    {
        return Jaminan::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Jaminan::create($data);
    }

    public function update($data, $id)
    {
        $jaminan = Jaminan::findOrFail($id);
        $jaminan->update($data);

        return $jaminan;
    }
}