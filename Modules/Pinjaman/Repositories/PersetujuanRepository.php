<?php

namespace Modules\Pinjaman\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Pinjaman\Entities\Pembayaran;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Persetujuan;
use Modules\Pinjaman\Entities\Pinjaman;

class PersetujuanRepository {
    public function getByRole($role)
    {
        return Persetujuan::with('pengajuan')
        ->where('role', $role)
        ->where('status', 'menunggu')
        ->get();
    }

    public function getById($fields, $id)
    {
        return Persetujuan::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Persetujuan::create($data);
    }

    public function update($data, $id)
    {
        $persetujuan = Persetujuan::findOrFail($id);
        $persetujuan->update($data);

        return $persetujuan;
    }

    public function getPinjamanSummary()
    {
        $pencairan = PengajuanPinjaman::where('status_pengajuan', 'pencairan')
                    ->get();
        
        $jumlahPencairan = $pencairan->count();

        $pembayaran = Pembayaran::where('status_pembayaran', 'verifikasi')
                    ->get();

        $jumlahPembayaran = $pembayaran->count();

        $pinjaman = Pinjaman::where('status_pinjaman', 'aktif')
                    ->with('angsuran')
                    ->get();

        // Semua angsuran yang belum lunas
        $angsuranBelumLunas = $pinjaman->flatMap(function ($item) {
            return $item->angsuran
                ->where('status_bayar', 'belum_bayar');
        });

        // Angsuran yang jatuh tempo bulan ini dan belum lunas
        $angsuranBulanIni = $angsuranBelumLunas->whereBetween('tanggal_jatuh_tempo', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ]);

        $totalAngsuranBulanIni = $angsuranBulanIni->count();

        return [
            'jumlahPencairan' => $jumlahPencairan,
            'jumlahPembayaran' => $jumlahPembayaran,
            'totalAngsuranBulanIni' => $totalAngsuranBulanIni,
        ];
    }
}