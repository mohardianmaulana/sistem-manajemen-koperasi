<?php

namespace Modules\Simpanan\Repositories;

use App\Models\Core\User;
use Modules\Simpanan\Entities\MasterSimpananWajib;
use Modules\Simpanan\Entities\SimpananWajib;

class SimpananWajibRepository
{
    /**
     * Menampilkan seluruh data simpanan wajib.
     */
    public function getAll($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = MasterSimpananWajib::with('user');

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

    /**
     * Menyimpan master simpanan wajib.
     */
    public function store(array $data)
    {
        return MasterSimpananWajib::create($data);
    }

    /**
     * Mengambil seluruh anggota.
     */
    public function getAllAnggota()
    {
        return User::role('anggota')->get();
    }

    /**
     * Detail berdasarkan id.
     */
    public function findById($id)
    {
        return MasterSimpananWajib::findOrFail($id);
    }

    /**
     * Update data.
     */
    public function update($master, array $data)
    {
        $master->update($data);

        return $master;
    }

    /**
     * Menyimpan ke tabel simpanan wajib final.
     */
    public function storeSimpanan(array $data)
    {
        return SimpananWajib::updateOrCreate(
            [
                'periode'    => $data['periode'],
                'id_anggota' => $data['id_anggota'],
            ],
            [
                'nilai'      => $data['nilai'],
                'periode'    => $data['periode'],
                'id_anggota' => $data['id_anggota'],
            ]
        );
    }

    /**
     * Mengecek apakah data final sudah ada.
     */
    public function existsSimpanan($idAnggota, $periode)
    {
        return SimpananWajib::where('id_anggota', $idAnggota)
            ->whereDate('periode', $periode)
            ->exists();
    }

    /**
     * Export autodebit.
     */
    public function exportAutoDebit($bulan = null, $tahun = null)
    {
        $query = MasterSimpananWajib::query()
            ->join('users', 'users.id', '=', 'master_simpanan_wajib.id_anggota')
            ->where('master_simpanan_wajib.status', 'pending');

        if (!is_null($bulan)) {
            $query->whereMonth('master_simpanan_wajib.periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('master_simpanan_wajib.periode', $tahun);
        }

        return $query->select(
                'users.name',
                'users.no_rek',
                'master_simpanan_wajib.nilai',
                'master_simpanan_wajib.periode'
            )
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Total autodebit.
     */
    public function totalAutoDebit($bulan = null, $tahun = null)
    {
        $query = MasterSimpananWajib::query()
            ->where('status', 'pending');

        if (!is_null($bulan)) {
            $query->whereMonth('periode', $bulan);
        }

        if (!is_null($tahun)) {
            $query->whereYear('periode', $tahun);
        }

        return $query->sum('nilai');
    }

    /**
     * Ringkasan.
     */
    public function getSummary($idAnggota = null, $bulan = null, $tahun = null)
    {
        $query = MasterSimpananWajib::query();

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

    /**
     * Mengambil periode terakhir.
     */
    public function getLastPeriode()
    {
        return MasterSimpananWajib::latest('periode')->first();
    }

    /**
     * Mengecek apakah periode sudah dibuat.
     */
    public function periodeExists($bulan, $tahun)
    {
        return MasterSimpananWajib::whereMonth('periode', $bulan)
            ->whereYear('periode', $tahun)
            ->exists();
    }
}