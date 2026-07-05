<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_simpanan_sukarela()
    {
        $response = $this->get('/simpanan-sukarela',[
            'nilai' => 50000,
            'periode' => now(),
            'id_anggota' => 1,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('simpanan_sukarela', [

            'nilai' => 50000,
            'id_anggota' => 1,

        ]);
    }
    public function test_update_simpanan_sukarela()
    {
        $response = $this->put('/simpanan-sukarela/1', [

            'nilai' => 60000,
            'periode' => now(),
            'id_anggota' => 1,

        ]);

         $response->assertStatus(200);

          $this->assertDatabaseHas('simpanan_sukarela', [

            'nilai' => 60000,
            'id_anggota' => 1,

        ]);
    }
}