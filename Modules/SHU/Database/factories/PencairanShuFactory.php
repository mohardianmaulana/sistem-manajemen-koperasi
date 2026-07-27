<?php
namespace Modules\SHU\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\SHU\Entities\PencairanShu;

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
                'id_shu_anggota' => null,

                'nominal_pengajuan' => 500000,

                'tanggal_pengajuan' => Carbon::today(),

                'tanggal_persetujuan' => null,

                'tanggal_pencairan' => null,

                'status' => PencairanShu::STATUS_MENUNGGU,

                'keterangan' => null,

                'disetujui_oleh' => null,

                'bukti' => null,
            ];
        }
}

