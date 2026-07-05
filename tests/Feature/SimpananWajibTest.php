<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimpananWajibTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_simpanan_wajib()
    {
          $response = $this->post('/simpanan-wajib', [

            'nilai' => 50000,
            'periode' => now(),
            'id_anggota' => 1,

        ]);


        // sesuai kontrak response yang diharapkan
        $response->assertStatus(200);


        // memastikan data tersimpan
        $this->assertDatabaseHas('simpanan_wajib', [

            'nilai' => 50000,
            'id_anggota' => 1,

        ]);
    }

    public function test_update_simpanan_wajib()
    {
          $response = $this->put('/simpanan-wajib/1', [

            'nilai' => 75000,
            'periode' => now(),
            'id_anggota' => 1,

        ]);


        $response->assertStatus(200);


        $this->assertDatabaseHas('simpanan_wajib', [

            'id' => 1,
            'nilai' => 75000,
            'id_anggota' => 1,

        ]);

    }

}