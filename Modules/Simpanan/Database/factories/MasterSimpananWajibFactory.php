<?php
namespace Modules\Simpanan\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MasterSimpananWajibFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Simpanan\Entities\MasterSimpananWajib::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'nilai'      => $this->faker->numberBetween(10000, 500000),
                'periode'    => $this->faker->date(),
                'tahun'      => $this->faker->year(),
                'status'     => 'pending',
                'bukti'      => null,
                'id_anggota' => 1,
            ];
    }
}

