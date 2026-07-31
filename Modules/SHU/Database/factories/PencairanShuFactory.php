<?php
namespace Modules\SHU\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Entities\ShuAnggota;

class PencairanShuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\SHU\Entities\PencairanShu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
        {
             return [

            'kode_pencairan' => 'SHU-2026-0001',

            'id_shu_anggota' => ShuAnggota::factory(),

            'nominal_pencairan' => 500000,

            'tanggal_pencairan' => Carbon::today(),

            'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,

            'keterangan' => null,

            'dicairkan_oleh' => null,

            'bukti' => null,

        ];
        }

        
}

