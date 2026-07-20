<?php
namespace Modules\SHU\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShuKoperasiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\SHU\Entities\ShuKoperasi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'periode_awal' => '2026-01-01',
                'periode_akhir' => '2026-12-31',
                'total_shu' => 1000000,
                'jasa_simpanan' => 400000,
                'jasa_pinjaman' => 200000,
                'dana_cadangan' => 200000,
                'jasa_pengurus' => 100000,
                'dana_sosial' => 100000,
            ];
    }
}

