<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Persetujuan;

class PersetujuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Persetujuan::class;
    
    public function definition()
    {
        return [
            'disetujui_oleh' => null,
            'status' => 'menunggu',
            'tanggal_disetujui' => null,
            'catatan' => null,
        ];
    }
}

