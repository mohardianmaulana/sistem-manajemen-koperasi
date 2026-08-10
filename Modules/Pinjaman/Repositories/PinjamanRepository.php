<?php

namespace Modules\Pinjaman\Repositories;

use Illuminate\Support\Carbon;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;

class PinjamanRepository {
    public function getAll($fields)
    {
        return Pinjaman::select($fields)->latest()->get();
    }

    public function getById($fields, $id)
    {
        return Pinjaman::select($fields)->findOrFail($id);
    }

    public function create($data)
    {
        return Pinjaman::create($data);
    }

    public function update($data, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        return $pinjaman->update($data);
    }

    public function cekPinjamanAktif($user_id)
    {
        return PengajuanPinjaman::where('id_anggota', $user_id)
            ->where('status_pengajuan', '!=', 'ditolak') // mengambil data pengajuan selain ditolak
            ->where(function ($query) {
                $query->whereDoesntHave('pinjaman') // ada pengajuan tapi belum ada pinjaman = true
                            ->orWhereHas('pinjaman', function ($q) {
                            $q->where('status_pinjaman', '!=', 'selesai'); // ada pengajuan, ada pinjaman, status selain selesai = true
                        });
            })
            ->exists();
    }

    public function getByAnggota($fields, $idAnggota)
    {
        return Pinjaman::select($fields)
            ->with([
                'pengajuan'
            ])
            ->where('status_pinjaman', 'aktif')
            ->withSum([
                'angsuran as total_dibayar' => function ($query) {
                    $query->where('status_bayar', 'lunas');
                }
            ], 'jumlah_angsuran')
            ->whereHas('pengajuan', function ($query) use ($idAnggota) {
                $query->where('id_anggota', $idAnggota);
            })
            ->latest()
            ->get();
    }

    public function monitoring($status = null, $skema = null)
    {
        $query = Pinjaman::with([
            'pengajuan.users',
            'pengajuan.skemaPinjaman',
            'angsuran'
        ]);

        if (!empty($status)) {
            $query->where('status_pinjaman', $status);
        }

        if (!empty($skema)) {
            $query->whereHas('pengajuan', function ($q) use ($skema) {
                $q->where('id_skema_pinjaman', $skema);
            });
        }

        return $query->latest()->get();
    }

    public function getPinjamanSummary($idAnggota)
    {
        $pinjaman = Pinjaman::whereHas('pengajuan', function ($query) use ($idAnggota) {
                        $query->where('id_anggota', $idAnggota);
                    })
                    ->where('status_pinjaman', 'aktif')
                    ->with('angsuran')
                    ->get();

        // Semua angsuran yang belum lunas
        $angsuranBelumLunas = $pinjaman->flatMap(function ($item) {
            return $item->angsuran
                ->whereNotIn('status_bayar', ['lunas', 'verifikasi']);
        });

        $sisaPinjaman = $angsuranBelumLunas->sum('jumlah_angsuran');

        $sisaBulan = $angsuranBelumLunas->count();

        // Angsuran yang jatuh tempo bulan ini dan belum lunas
        $angsuranBulanIni = $angsuranBelumLunas->filter(function ($angsuran) {
            return Carbon::parse($angsuran->tanggal_jatuh_tempo)
                ->lte(Carbon::now());
        });

        $jumlahAngsuranBulanIni = $angsuranBulanIni->count();

        $totalAngsuranBulanIni = $angsuranBulanIni
            ->sum('jumlah_angsuran');

        return [
            'sisa' => $sisaPinjaman,
            'sisaBulan' => $sisaBulan,
            'angsuranBulanIni' => [
                'jumlah' => $jumlahAngsuranBulanIni,
                'total' => $totalAngsuranBulanIni,
                'detail' => $angsuranBulanIni,
            ],
        ];
    }
}