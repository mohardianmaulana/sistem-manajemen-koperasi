<?php

namespace Modules\Simpanan\Repositories;

use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Modules\Simpanan\Entities\SimpananSukarela;

class SimpananSukarelaRepository
{
    public function getAll($idAnggota = null)
    {
        $query = MasterSimpananSukarela::with('user');

        if ($idAnggota !== null) {
            $query->where('id_anggota', $idAnggota);
        }

        return $query->paginate(5);
    }

    public function store(array $data)
    {
        return MasterSimpananSukarela::create($data);
    }

    public function findById($id)
    {
        return MasterSimpananSukarela::findOrFail($id);
    }

    public function update($master, array $data)
    {
        $master->update($data);

        return $master;
    }

    public function storeSimpanan(array $data)
    {
        return SimpananSukarela::create($data);
    }

    public function sudahMasukSimpanan($master)
    {
        return SimpananSukarela::where([
            'id_anggota' => $master->id_anggota,
            'periode'    => $master->periode,
        ])->exists();
    }

    public function exportAutoDebit()
    {
        return MasterSimpananSukarela::query()
            ->join('users','users.id','=','master_simpanan_sukarela.id_anggota')
            ->where('master_simpanan_sukarela.status','pending')
            ->select('users.name','users.no_rek','master_simpanan_sukarela.nilai')
            ->orderBy('users.name')
            ->get();
    }

    public function totalAutoDebit()
    {
        return MasterSimpananSukarela::
            where('status','pending')
            ->sum('nilai');
    }
}