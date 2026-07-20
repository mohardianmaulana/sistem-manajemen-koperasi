<?php
namespace Modules\Simpanan\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TabunganFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Simpanan\Entities\SimpananPokok::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nilai' => $this->faker->numberBetween(10000, 100000),
            'tanggal' => now(),
            'status' => 'pending',
            'bukti' => null,
            'id_anggota' => 1,
        ];
    }
}

