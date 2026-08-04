<?php
namespace Modules\Simpanan\Database\factories;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Simpanan\Entities\PencairanSimpanan;

class PencairanSimpananFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Simpanan\Entities\PencairanSimpanan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kode_pencairan' => 'PCS-' . $this->faker->unique()->numerify('######'),

            'nominal_pencairan' => $this->faker->numberBetween(
                100000,
                1000000
            ),

            'alasan' => $this->faker->sentence(),

            'status' => PencairanSimpanan::STATUS_PENDING,

            'tanggal_verifikasi' => null,

            'tanggal_pencairan' => null,

            'catatan' => null,

            'bukti_transfer' => null,

            'id_anggota' => User::factory(),

            'id_verifikator' => null,

            'id_bendahara' => null,
        ];
    }

    public function diverifikasi()
    {
        return $this->state(function () {
            return [
                'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
            ];
        });
    }

    public function ditolak()
    {
        return $this->state(function () {
            return [
                'status' => PencairanSimpanan::STATUS_DITOLAK,
            ];
        });
    }

    public function dicairkan()
    {
        return $this->state(function () {
            return [
                'status' => PencairanSimpanan::STATUS_DICAIRKAN,
            ];
        });
    }

    public function gagal()
    {
        return $this->state(function () {
            return [
                'status' => PencairanSimpanan::STATUS_GAGAL,
            ];
        });
    }
}