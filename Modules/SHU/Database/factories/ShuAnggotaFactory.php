<?php
namespace Modules\SHU\Database\factories;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShuAnggotaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\SHU\Entities\ShuAnggota::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
   public function definition()
    {
        return [
            'shu_simpanan' => 1000000,
            'shu_pinjaman' => 500000,
            'pajak' => 50000,
            'shu_anggota' => 1450000,
            'periode_awal' => '2026-01-01',
            'periode_akhir' => '2026-12-31',
            'id_anggota' => User::factory(),
        ];
    }

    
}

