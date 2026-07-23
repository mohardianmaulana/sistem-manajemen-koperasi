<?php

namespace Modules\Simpanan\Repositories;

use App\Models\Core\User;
use Modules\Simpanan\Entities\MasterSimpananWajib;
use Modules\Simpanan\Entities\SimpananPokok;

class SimpananPokokRepository
{
    public function getAll($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = SimpananPokok::with('user');

        if (!is_null($idAnggota)) {
            $query->where('id_anggota', $idAnggota);
        }

        if (!empty($bulan)) {
            $query->whereMonth('tanggal', $bulan);
        }

        if (!empty($tahun)) {
            $query->whereYear('tanggal', $tahun);
        }

        return $query->latest()->paginate(10)->withQueryString();
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

    public function getSummary($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = SimpananPokok::query();

        if (!is_null($idAnggota)) {
            $query->where('id_anggota', $idAnggota);
        }

        if (!empty($bulan)) {
            $query->whereMonth('tanggal', $bulan);
        }

        if (!empty($tahun)) {
            $query->whereYear('tanggal', $tahun);
        }

        return [
            'totalNominal' => (clone $query)->sum('nilai'),

            'pending' => (clone $query)
                ->where('status', 'pending')
                ->count(),

            'selesai' => (clone $query)
                ->where('status', 'selesai')
                ->count(),
        ];
    }
}