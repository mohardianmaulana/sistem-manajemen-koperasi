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
            'id_pengajuan' => 1,

            'jumlah_disetujui' => 5000000,

            'jumlah_bunga' => 500000,

            'total_pinjaman' => 5500000,

            'tanggal_disetujui' => now(),

            'status_pinjaman' => 'aktif',
        ];
    }
}

