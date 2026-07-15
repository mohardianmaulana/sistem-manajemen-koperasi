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
            'tahun'          => 2026,
            'jasa_simpanan'  => 1000000,
            'jasa_pinjaman'  => 500000,
            'dana_cadangan'  => 200000,
            'jasa_pengurus'  => 150000,
            'dana_sosial'    => 100000,
            'total_shu'      => 1950000,
        ];
    }
}

