<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Pinjaman;

class PinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Pinjaman::class;

    public function definition()
    {
        return [
            'jumlah_disetujui' => 500000,
            'tanggal_disetujui' => '2026-06-01',
            'jumlah_bunga' => '90000',
            'total_pinjaman' => '590000',
            'status_pinjaman' => 'aktif',
        ];
    }
}

