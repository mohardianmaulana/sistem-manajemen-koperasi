<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Role;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
        ];
    }
}

