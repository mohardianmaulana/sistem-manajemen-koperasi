<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\Jaminan;

class JaminanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Jaminan::class;

    public function definition()
    {
        return [
            'nama' => 'Surat tanah',
            'deskripsi' => 'Ini surat tanah',
        ];
    }
}

