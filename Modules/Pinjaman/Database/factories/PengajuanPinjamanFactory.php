<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\PengajuanPinjaman;

class PengajuanPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = PengajuanPinjaman::class;

    public function definition()
    {
        return [
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ];
    }
}

