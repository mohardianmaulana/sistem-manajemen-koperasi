<?php
namespace Modules\Rat\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Rat\Entities\Rat;

class RatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Rat\Entities\Rat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_BELUM,
        ];
    }
}

