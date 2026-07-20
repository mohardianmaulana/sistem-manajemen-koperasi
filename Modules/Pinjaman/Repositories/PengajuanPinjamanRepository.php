<?php

namespace Modules\Pinjaman\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Pinjaman\Entities\PengajuanPinjaman;

class PengajuanPinjamanRepository {
    public function getAll($fields)
    {
        return PengajuanPinjaman::select($fields)->whereIn('status_pengajuan', ['menunggu', 'verifikasi', 'persetujuan_akhir'])->latest()->get();
    }

    public function getPencairan($fields)
    {
        return PengajuanPinjaman::where('status_pengajuan', 'pencairan')->select($fields)->latest()->get();
    }

    public function getDetail($id)
    {
        return PengajuanPinjaman::with([
            'skemaPinjaman',
            'jaminan'
        ])->findOrFail($id);
    }

    public function getById($fields, $id)
    {
        return PengajuanPinjaman::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return PengajuanPinjaman::create($data);
    }

    public function update($data, $id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $pengajuan_pinjaman->update($data);

        return $pengajuan_pinjaman;
    }

    public function delete($id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $pengajuan_pinjaman->delete();
    }

    public function getPivotJaminan($idPengajuan, $idJaminan)
    {
        return DB::table('pengajuan_jaminan')
            ->where('id_pengajuan', $idPengajuan)
            ->where('id_jaminan', $idJaminan)
            ->first();
    }

    public function attachJaminan($pengajuan, $id_jaminan, $file)
    {
        return $pengajuan
            ->jaminan()
            ->attach(
                $id_jaminan,
                [
                    'file_jaminan'=>$file,
                    'status_verifikasi'=>'menunggu'
                ]
            );
    }

    public function getJaminan($pengajuan, $idJaminan)
    {
        return $pengajuan->jaminan()
            ->where('id_jaminan', $idJaminan)
            ->first();
    }

    public function updateJaminan($pengajuan, $idJaminan, $data)
    {
        return $pengajuan->jaminan()
            ->updateExistingPivot($idJaminan, $data);
    }

    public function getByAnggota($fields, $idAnggota)
    {
        return PengajuanPinjaman::select($fields)
            ->with([
                'skemaPinjaman',
                'persetujuan',
            ])
            ->where('id_anggota', $idAnggota)
            ->latest()
            ->get();
    }

    public function updatePivotJaminan($idPengajuan, $idJaminan, $data)
    {
        $pengajuan = PengajuanPinjaman::findOrFail($idPengajuan);

        $pengajuan->jaminan()->updateExistingPivot(
            $idJaminan,
            $data
        );
    }

    public function masihAdaJaminanBelumTerverifikasi($idPengajuan)
    {
        $pengajuan = PengajuanPinjaman::findOrFail($idPengajuan);

        return $pengajuan->jaminan()
            ->wherePivot('status_verifikasi', '!=', 'verifikasi')
            ->exists();
    }

    public function detachJaminan($id)
    {
        $pengajuanPinjaman = PengajuanPinjaman::findOrFail($id);

        $pengajuanPinjaman->jaminan()->detach();
    }
}