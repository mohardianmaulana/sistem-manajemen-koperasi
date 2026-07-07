<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Angsuran;

class AngsuranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Angsuran::class;

    public function definition()
    {
        return [
             // Akan dioverride pada test
            'id_pinjaman' => 1,

            'angsuran_ke' => 1,

            'jumlah_angsuran' => 500000,

            'tanggal_jatuh_tempo' => now()->toDateString(),

            'status_bayar' => 'belum_bayar',
        ];
    }
}

