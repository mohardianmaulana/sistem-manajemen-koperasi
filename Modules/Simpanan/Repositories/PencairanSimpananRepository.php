<?php

namespace Modules\Simpanan\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Simpanan\Entities\PencairanSimpanan;

class PencairanSimpananRepository
{
   public function getAll()
    {
        $query = PencairanSimpanan::with([
            'anggota',
            'verifikator',
            'bendahara'
        ]);

        if (Auth::user()->hasRole('anggota')) {
            $query->where('id_anggota', Auth::id());
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('kode')) {
            $query->where(
                'kode_pencairan',
                'like',
                '%' . request('kode') . '%'
            );
        }

        if (
            !Auth::user()->hasRole('anggota') &&
            request()->filled('nama')
        ) {
            $query->whereHas('anggota', function ($q) {
                $q->where(
                    'name',
                    'like',
                    '%' . request('nama') . '%'
                );
            });
        }

        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function getById($id)
    {
        return PencairanSimpanan::with([
            'anggota',
            'verifikator',
            'bendahara'
        ])->findOrFail($id);
    }

    public function getByKode($kode)
    {
        return PencairanSimpanan::where('kode_pencairan', $kode)
            ->first();
    }

    public function store(array $data)
    {
        return PencairanSimpanan::create($data);
    }

    public function update($id, array $data)
    {
        $pencairan = $this->getById($id);

        $pencairan->update($data);

        return $pencairan;
    }

    public function delete($id)
    {
        return $this->getById($id)->delete();
    }

    public function getByStatus($status)
    {
        return PencairanSimpanan::with('anggota')
            ->where('status', $status)
            ->latest()
            ->paginate(10);
    }

    public function getByAnggota($idAnggota)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10);
    }

    public function totalPencairanAnggota($idAnggota)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->where('status', PencairanSimpanan::STATUS_DICAIRKAN)
            ->sum('nominal_pencairan');
    }

    public function totalPendingAnggota($idAnggota)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->where('status', PencairanSimpanan::STATUS_PENDING)
            ->sum('nominal_pencairan');
    }

    public function totalPending()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_PENDING
        )->count();
    }

    public function totalDicairkan($idAnggota)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->where('status', PencairanSimpanan::STATUS_DICAIRKAN)
            ->sum('nominal_pencairan');
    }

    public function totalDiverifikasi()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_DIVERIFIKASI
        )->count();
    }

    public function totalDitolak()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_DITOLAK
        )->count();
    }

    public function totalSiapDicairkan()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_DIVERIFIKASI
        )->count();
    }

    public function totalSudahDicairkan()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_DICAIRKAN
        )->count();
    }

    public function totalGagal()
    {
        return PencairanSimpanan::where(
            'status',
            PencairanSimpanan::STATUS_GAGAL
        )->count();
    }

    public function getByIdAnggota($id, $idAnggota)
    {
        return PencairanSimpanan::with([
            'anggota',
            'verifikator',
            'bendahara'
        ])
        ->where('id', $id)
        ->where('id_anggota', $idAnggota)
        ->firstOrFail();
    }

    public function hasPendingRequest($idAnggota)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->whereIn('status', [
                PencairanSimpanan::STATUS_PENDING,
                PencairanSimpanan::STATUS_DIVERIFIKASI,
            ])
            ->exists();
    }

    public function hasPencairanByTahun($idAnggota, $tahun)
    {
        return PencairanSimpanan::where('id_anggota', $idAnggota)
            ->whereYear('tanggal_pencairan', $tahun)
            ->exists();
    }
}