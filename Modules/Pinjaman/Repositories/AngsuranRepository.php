<?php

namespace Modules\Pinjaman\Repositories;

use Modules\Pinjaman\Entities\Angsuran;
use Carbon\Carbon;

class AngsuranRepository {

    public function getTagihanBulanIni($fields)
    {
        $tagihan = Angsuran::select($fields)
            ->where('status_bayar', 'belum_bayar')
            ->whereBetween('tanggal_jatuh_tempo', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->latest()
            ->get();

        foreach ($tagihan as $angsuran) {

            $tunggakan = Angsuran::where('id_pinjaman', $angsuran->id_pinjaman)
                ->where('status_bayar', 'gagal_debet')
                ->whereDate('tanggal_jatuh_tempo', '<', $angsuran->tanggal_jatuh_tempo)
                ->sum('jumlah_angsuran');

            $angsuran->tunggakan = $tunggakan;
            $angsuran->total_tagihan = $angsuran->jumlah_angsuran + $tunggakan;
        }

        return $tagihan;
    }

    public function getTunggakan($idPinjaman, $tanggalJatuhTempo)
    {
        return Angsuran::where('id_pinjaman', $idPinjaman)
            ->where('status_bayar', 'gagal_debet')
            ->whereDate('tanggal_jatuh_tempo', '<', $tanggalJatuhTempo)
            ->get();
    }

    public function getTunggakanVerifikasi($idPinjaman, $tanggalJatuhTempo)
    {
        return Angsuran::where('id_pinjaman', $idPinjaman)
            ->where('status_bayar', 'verifikasi')
            ->whereDate('tanggal_jatuh_tempo', '<', $tanggalJatuhTempo)
            ->orderBy('tanggal_jatuh_tempo')
            ->get();
    }

    public function getTunggakanGagal($idPinjaman, $tanggalJatuhTempo)
    {
        return Angsuran::where('id_pinjaman', $idPinjaman)
            ->where('status_bayar', 'gagal_verifikasi')
            ->whereDate('tanggal_jatuh_tempo', '<', $tanggalJatuhTempo)
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

    public function existsBelumLunas($id)
    {
        $masihAdaAngsuran = Angsuran::where('id_pinjaman', $id)
        ->where('status_bayar', '!=', 'lunas')
        ->exists();

        return $masihAdaAngsuran;
    }

    public function getAngsuran($id)
    {
        $angsuran = Angsuran::with([
            'pinjaman.pengajuan.users',
            'pembayaran'
        ])
        ->whereHas('pinjaman', function ($query) {
            $query->where('status_pinjaman', 'aktif');
        })
        ->whereHas(
            'pinjaman.pengajuan', function ($query) use ($id) { 
            $query->where('id_anggota', $id);
        })->get();

        foreach ($angsuran as $item) {

            $tunggakan = Angsuran::where('id_pinjaman', $item->id_pinjaman)
                ->whereIn('status_bayar', ['gagal_debet', 'gagal_verifikasi'])
                ->whereDate('tanggal_jatuh_tempo', '<', $item->tanggal_jatuh_tempo)
                ->sum('jumlah_angsuran');

            $item->total_tagihan = $item->jumlah_angsuran + $tunggakan;
        }
        
        return $angsuran;
    }

    public function getVerifikasi($fields)
    {
        $angsuran = Angsuran::select($fields)
                    ->where('status_bayar', 'verifikasi')
                    ->get();
        return $angsuran;
    }
}