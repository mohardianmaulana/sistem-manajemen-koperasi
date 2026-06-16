<?php

namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\SkemaPinjaman;

class SkemaPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = SkemaPinjaman::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->randomElement([
                'Pinjaman Reguler',
                'Pinjaman Pendidikan',
                'Pinjaman Usaha',
                'Pinjaman Darurat',
            ]),
            'min_nominal' => 500000,
            'max_nominal' => 10000000,
            'min_tenor' => 1,
            'max_tenor' => 24,
            'bunga' => 1.00,
            'jaminan' => $this->faker->randomElement([
                'tidak',
                'ada',
            ]),
            'deskripsi' => $this->faker->sentence(),
            'status' => 'aktif'
        ];
    }
}
