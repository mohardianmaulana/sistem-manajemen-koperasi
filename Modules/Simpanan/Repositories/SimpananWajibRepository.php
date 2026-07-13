<?php

namespace Modules\Simpanan\Repositories;

use Modules\Simpanan\Entities\MasterSimpananWajib;
use Modules\Simpanan\Entities\SimpananWajib;

class SimpananWajibRepository
{
    /**
     * Menampilkan seluruh data simpanan wajib anggota.
     */
    public function getAll($idAnggota = null)
    {
         $query = MasterSimpananWajib::with('user');

        if ($idAnggota !== null) {
            $query->where('id_anggota', $idAnggota);
        }

        return $query->paginate(5);
    }

    /**
     * Menyimpan pengajuan simpanan wajib.
     */
    public function store(array $data)
    {
        return MasterSimpananWajib::create($data);
    }

    /**
     * Mencari data berdasarkan id.
     */
    public function findById($id)
    {
        return MasterSimpananWajib::findOrFail($id);
    }

    /**
     * Update data master.
     */
    public function update($master, array $data)
    {
        $master->update($data);

        return $master;
    }

    /**
     * Memasukkan ke tabel simpanan wajib final.
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
}