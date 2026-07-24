<?php

namespace Modules\Unit\Repositories;

use App\Models\Core\Unit;

class UnitRepository
{
   public function getAll($search = null)
    {
        return Unit::when($search, function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();
    }

    public function store(array $data)
    {
        return Unit::create($data);
    }

    public function findById($id)
    {
        return Unit::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $unit = Unit::findOrFail($id);

        $unit->update($data);

        return $unit;
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        return $unit->delete();
    }
}