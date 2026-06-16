<?php
namespace Modules\Pinjaman\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pinjaman\Entities\PengajuanPinjaman;

class PengajuanPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = PengajuanPinjaman::class;

    public function definition()
    {
        return [
            'tanggal_pengajuan' => $this->faker->date(),
            'jumlah_pengajuan' => 5000000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => $this->faker->phoneNumber(),
            'no_ktp' => $this->faker->numerify('################'),
            'no_rekening' => $this->faker->bankAccountNumber(),
            'alamat' => $this->faker->address(),
            'nama_istri_suami' => $this->faker->name(),
            'path_form_pinjaman' => 'form_pinjaman/sample.pdf',
            'path_dokumen' => 'dokumen/sample.pdf',
        ];
    }
}

