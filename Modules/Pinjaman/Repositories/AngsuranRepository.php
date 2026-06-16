<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Angsuran;
use Carbon\Carbon;

class AngsuranRepository {

    public function getAll($fields)
    {
        return Angsuran::select($fields)
            ->where('status_bayar', 'belum_bayar')
            ->whereBetween('tanggal_jatuh_tempo', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->latest()
            ->get();
    }

    public function getById($fields, $id)
    {
        return Angsuran::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Angsuran::create($data);
    }

    public function update($data, $id)
    {
        $angsuran = Angsuran::findOrFail($id);
        $angsuran->update($data);

        return $angsuran;
    }

    public function getAngsuran($id)
    {
        $angsuran = Angsuran::whereHas(
            'pinjaman.pengajuan', function ($query) use ($id) { 
            $query->where('id_anggota', $id);
        })->get();
        
        return $angsuran;
    }

    public function getVerifikasi($fields)
    {
        $angsuran = Angsuran::select($fields)->where('status_bayar', 'gagal_debet')->get();
        return $angsuran;
    }
}