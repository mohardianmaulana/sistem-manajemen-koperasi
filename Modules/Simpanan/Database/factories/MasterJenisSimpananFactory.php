<?php
namespace Modules\Simpanan\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MasterJenisSimpananFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Simpanan\Entities\MasterJenisSimpanan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_jenis_simpanan' => 'Simpanan Sukarela',
            'tanggal_mulai'       => now()->subDay(),
            'tanggal_berakhir'    => now()->addDay(),
        ];
    }

     public function aktif()
    {
        return $this->state(function () {
            return [
                'tanggal_mulai'    => now()->subDay(),
                'tanggal_berakhir' => now()->addDay(),
            ];
        });
    }

    /**
     * State jadwal tidak aktif
     */
    public function tidakAktif()
    {
        return $this->state(function () {
            return [
                'tanggal_mulai'    => now()->subDays(10),
                'tanggal_berakhir' => now()->subDay(),
            ];
        });
    }
}

