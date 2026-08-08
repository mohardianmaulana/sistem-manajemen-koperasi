<?php

namespace Modules\SHU\Repositories;

use Modules\SHU\Entities\PencairanShu;

class PencairanShuRepository
{
    /**
     * Relasi yang selalu dimuat.
     */
    protected array $relations = [
        'shuAnggota.user',
        'pencair',
    ];

    /**
     * Menampilkan seluruh data pencairan.
     */
    public function getAll($status = null)
    {
        return PencairanShu::with($this->relations)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('tanggal_pencairan')
            ->paginate(10);
    }

    /**
     * Detail pencairan.
     */
    public function findById($id)
    {
        return PencairanShu::with($this->relations)
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

        return $pencairan->fresh($this->relations);
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
            ->sum('nominal_pencairan');
    }

    /**
     * Riwayat pencairan berdasarkan SHU anggota.
     */
    public function getRiwayatByShuAnggota($idShuAnggota)
    {
        return PencairanShu::with('pencair')
            ->where('id_shu_anggota', $idShuAnggota)
            ->latest('tanggal_pencairan')
            ->get();
    }

    /**
     * Total transaksi pencairan.
     */
    public function totalPencairan()
    {
        return PencairanShu::count();
    }

    /**
     * Total pencairan yang berhasil.
     */
    public function totalDicairkan()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_DICAIRKAN
        )->count();
    }

    /**
     * Total pencairan yang gagal.
     */
    public function totalGagal()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_GAGAL
        )->count();
    }

    /**
     * Total pencairan yang siap diproses.
     */
    public function totalSiapDicairkan()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_SIAP_DICAIRKAN
        )->count();
    }

    /**
     * Riwayat pencairan milik anggota.
     */
    public function getByAnggota($idAnggota)
    {
        return PencairanShu::with([
                'shuAnggota',
                'pencair',
            ])
            ->whereHas('shuAnggota', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })
            ->latest('tanggal_pencairan')
            ->paginate(10);
    }

    /**
     * Mengambil transaksi pencairan terakhir.
     */
    public function getLastPencairan()
    {
        return PencairanShu::latest()->first();
    }

    /**
     * Mengecek apakah SHU sudah pernah dicairkan.
     */
    public function sudahDicairkan($idShuAnggota)
    {
        return PencairanShu::where('id_shu_anggota', $idShuAnggota)
            ->where('status', PencairanShu::STATUS_DICAIRKAN)
            ->exists();
    }

    /**
     * Total nominal SHU yang telah dicairkan.
     */
    public function totalNominalDicairkanSemua()
    {
        return PencairanShu::where(
            'status',
            PencairanShu::STATUS_DICAIRKAN
        )->sum('nominal_pencairan');
    }

    /**
     * Mengambil transaksi pencairan terakhir berdasarkan SHU anggota.
     */
    public function getPencairanTerakhir($idShuAnggota)
    {
        return PencairanShu::where('id_shu_anggota', $idShuAnggota)
            ->latest('tanggal_pencairan')
            ->first();
    }

    public function existsByShuAnggota($idShuAnggota)
    {
        return PencairanShu::where(
            'id_shu_anggota',
            $idShuAnggota
        )->exists();
    }

    public function getLastNomor()
    {
        return PencairanShu::max('id') ?? 0;
    }

    public function getBendaharaSummary()
    {
        return [
            'menunggu' => PencairanShu::where(
                'status',
                PencairanShu::STATUS_SIAP_DICAIRKAN
            )->count(),

            'berhasil' => PencairanShu::where(
                'status',
                PencairanShu::STATUS_DICAIRKAN
            )->count(),

            'gagal' => PencairanShu::where(
                'status',
                PencairanShu::STATUS_GAGAL
            )->count(),
        ];
    }
}