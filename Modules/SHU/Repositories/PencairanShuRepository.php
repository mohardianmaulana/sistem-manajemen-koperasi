<?php

namespace Modules\SHU\Repositories;

use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Entities\ShuAnggota;

class PencairanShuRepository
{
    /**
     * Menampilkan seluruh data pencairan.
     */
    public function getAll($status = null)
    {
        return PencairanShu::with([
                'shuAnggota.user',
                'approver'
            ])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(10);
    }

    /**
     * Detail pencairan.
     */
    public function findById($id)
    {
        return PencairanShu::with([
                'shuAnggota.user',
                'approver'
            ])
            ->findOrFail($id);
    }

    /**
     * Menyimpan data pencairan.
     */
    public function store(array $data)
    {
        return PencairanShu::create($data);
    }

    /**
     * Mengubah data pencairan.
     */
    public function update($id, array $data)
    {
        $pencairan = $this->findById($id);

        $pencairan->update($data);

        return $pencairan->fresh([
            'shuAnggota.user',
            'approver'
        ]);
    }

    /**
     * Menghapus data pencairan.
     */
    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    /**
     * Total nominal SHU yang sudah dicairkan.
     */
    public function totalNominalDicairkan($idShuAnggota)
    {
        return PencairanShu::where('id_shu_anggota', $idShuAnggota)
            ->where('status', PencairanShu::STATUS_DICAIRKAN)
            ->sum('nominal_pengajuan');
    }

    /**
     * Total nominal SHU yang masih diproses.
     */
    public function totalNominalDiproses($idShuAnggota)
    {
        return PencairanShu::where('id_shu_anggota', $idShuAnggota)
            ->whereIn('status', [
                PencairanShu::STATUS_MENUNGGU,
                PencairanShu::STATUS_DISETUJUI
            ])
            ->sum('nominal_pengajuan');
    }

    /**
     * Riwayat pencairan berdasarkan SHU anggota.
     */
    public function getRiwayatByShuAnggota($idShuAnggota)
    {
        return PencairanShu::where('id_shu_anggota', $idShuAnggota)
            ->orderByDesc('tanggal_pengajuan')
            ->get();
    }

    public function totalPengajuan()
    {
        return PencairanShu::count();
    }

  public function totalMenunggu()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_MENUNGGU
        )->count();
    }

    public function totalDisetujui()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_DISETUJUI
        )->count();
    }
    
    public function totalDicairkan()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_DICAIRKAN
        )->count();
    }

    public function totalDitolak()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_DITOLAK
        )->count();
    }

    public function getByAnggota($idAnggota)
    {
        return PencairanShu::with('shuAnggota')
            ->whereHas('shuAnggota', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })
            ->latest()
            ->paginate(10);
    }
}