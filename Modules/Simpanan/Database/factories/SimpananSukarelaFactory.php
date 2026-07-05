<?php
namespace Modules\Simpanan\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Simpanan\Entities\SimpananSukarela;

class SimpananSukarelaFactory extends Factory
{
    protected $model = SimpananSukarela::class;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    /**
     * Define the model's default state.
     *
     * @return array
     */
      public function definition(): array
    {
        return [
            'nilai'      => $this->faker->numberBetween(10000, 500000),
            'periode'    => $this->faker->date(),
            'id_anggota' => 1,
        ];
    }
}

