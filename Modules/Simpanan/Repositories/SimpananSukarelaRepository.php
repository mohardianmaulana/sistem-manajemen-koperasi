<?php

namespace Modules\Simpanan\Repositories;

use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Modules\Simpanan\Entities\SimpananSukarela;

class SimpananSukarelaRepository
{
   public function getAll($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = MasterSimpananSukarela::with('user');

        if (!is_null($idAnggota)) {
            $query->where('id_anggota', $idAnggota);
        }

        if (!is_null($bulan)) {
            $query->whereMonth('periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('periode', $tahun);
        }

        return $query->latest('periode')
            ->paginate(10)
            ->withQueryString();
    }

    public function getSummary($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = MasterSimpananSukarela::query();

        if (!is_null($idAnggota)) {
            $query->where('id_anggota', $idAnggota);
        }

        if (!is_null($bulan)) {
            $query->whereMonth('periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('periode', $tahun);
        }

        return [

            'totalNominal' => (clone $query)->sum('nilai'),

            'pending' => (clone $query)
                ->where('status', 'pending')
                ->count(),

            'selesai' => (clone $query)
                ->where('status', 'selesai')
                ->count(),

            'totalAnggota' => (clone $query)
                ->distinct('id_anggota')
                ->count('id_anggota'),

        ];
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

     public function exportAutoDebit($bulan = null, $tahun = null)
    {
        $query = MasterSimpananSukarela::query()
            ->join('users', 'users.id', '=', 'master_simpanan_sukarela.id_anggota')
            ->where('master_simpanan_sukarela.status', 'pending');

        if (!is_null($bulan)) {
            $query->whereMonth('master_simpanan_sukarela.periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('master_simpanan_sukarela.periode', $tahun);
        }

        return $query->select(
                'users.name',
                'users.no_rek',
                'master_simpanan_sukarela.nilai',
                'master_simpanan_sukarela.periode'
            )
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Total autodebit.
     */
    public function totalAutoDebit($bulan = null, $tahun = null)
    {
        $query = MasterSimpananSukarela::query()
            ->where('status', 'pending');

        if (!is_null($bulan)) {
            $query->whereMonth('periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('periode', $tahun);
        }

        return $query->sum('nilai');
    }

    public function getMasterSiapGenerate()
    {
        return MasterSimpananSukarela::where('status', 'selesai')
            ->where('nilai', '>', 0)
            ->orderByDesc('periode')
            ->get()
            ->unique('id_anggota')
            ->values();
    }

    public function sudahAdaPeriode($idAnggota, $periode)
    {
        return MasterSimpananSukarela::where('id_anggota', $idAnggota)
            ->whereDate('periode', $periode)
            ->exists();
    }
    
    public function createPeriode(array $data)
    {
        return MasterSimpananSukarela::create($data);
    }
}