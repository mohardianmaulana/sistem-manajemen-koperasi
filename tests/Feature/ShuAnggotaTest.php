<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShuAnggotaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sistem_dapat_menyimpan_shu_anggota()
    {
         $response = $this->post('/shu-anggota/hitung', [

            'id_anggota' => 1,

        ]);


        // kontrak response
        $response->assertStatus(200);


        // hasil perhitungan harus tersimpan
        $this->assertDatabaseHas('shu_anggota', [

            'id_anggota' => 1,

        ]);

    }
}
