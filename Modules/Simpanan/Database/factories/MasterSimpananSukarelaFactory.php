<?php
namespace Modules\Simpanan\Database\factories;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Simpanan\Entities\MasterSimpananSukarela;

class MasterSimpananSukarelaFactory extends Factory
{
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
        protected $model = MasterSimpananSukarela::class;

        public function definition(): array
        {
            return [
                'nilai'      => $this->faker->numberBetween(10000, 500000),
                'periode'    => $this->faker->date(),
                'tahun'      => $this->faker->year(),
                'status'     => 'pending',
                'bukti'      => null,
                'id_anggota' => User::factory(),
            ];
        }

        // state: sudah disetujui
        public function selesai()
        {
            return $this->state(fn () => [
                'status' => 'selesai',
                'bukti'  => 'bukti/sample.jpg',
            ]);
        }

        // state: ditolak
        public function gagal()
        {
            return $this->state(fn () => [
                'status' => 'tidak berhasil',
            ]);
        }
}

