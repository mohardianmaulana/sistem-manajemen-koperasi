<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Anggota;
use Modules\Pinjaman\Entities\Role;
use Modules\Pinjaman\Entities\Unit;

class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Anggota::class;

    public function definition()
    {
        return [
            'nip' => $this->faker->numerify('##################'),
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'),
            'jabatan' => $this->faker->randomElement([
                'ketua',
                'bendahara',
                'sekretaris',
                'anggota'
            ]),
            'id_role' => Role::factory(),
            'id_unit' => Unit::factory(),
        ];
    }
}

