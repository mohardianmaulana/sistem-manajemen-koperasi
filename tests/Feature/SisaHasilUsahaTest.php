<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SisaHasilUsahaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
       $response = $this->post('/sisa-hasil-usaha', [

            'jasa_simpanan' => 4000000,

            'jasa_pinjaman' => 6000000,

            'dana_cadangan' => 2000000,

            'jasa_pengurus' => 1000000,

            'dana_sosial' => 1000000,

            'total_shu' => 14000000,

            'tahun' => 2026,

        ]);


        // kontrak response
        $response->assertStatus(200);


        // memastikan data tersimpan
        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'jasa_simpanan' => 4000000,

            'jasa_pinjaman' => 6000000,

            'total_shu' => 14000000,

            'tahun' => 2026,

        ]);

    
    }
}
