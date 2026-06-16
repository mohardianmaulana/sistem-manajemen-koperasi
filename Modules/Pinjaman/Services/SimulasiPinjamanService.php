<?php

namespace Modules\Pinjaman\Services;

class SimulasiPinjamanService {
    public function hitung_angsuran($nominal, $tenor, $skema)
    {
        $bungaPersen = $skema->bunga;

        // total bunga
        $totalBunga = ($nominal * $bungaPersen / 100) * $tenor;

        // total pembayaran
        $totalPembayaran = $nominal + $totalBunga;

        // angsuran per bulan
        $angsuranBulanan = $totalPembayaran / $tenor;

        $detail = [];

        for ($i = 1; $i <= $tenor; $i++) {

            $pokok = $nominal / $tenor;
            $bunga = $totalBunga / $tenor;
            $total = $pokok + $bunga;

            $detail[] = [
                'bulan' => $i,
                'pokok' => round($pokok),
                'bunga' => round($bunga),
                'total' => round($total),
            ];
        }

        return [
            'total_pinjaman' => $nominal,
            'total_bunga' => round($totalBunga),
            'total_pembayaran' => round($totalPembayaran),
            'angsuran_bulanan' => round($angsuranBulanan),
            'detail' => $detail
        ];
    }
}