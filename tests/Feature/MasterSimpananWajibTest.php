<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pinjaman\Entities\Anggota;
use Modules\Simpanan\Entities\MasterSimpananWajib;
use Tests\TestCase;

class MasterSimpananWajibTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_master_simpanan_wajib()
     {
       
        $anggota = Anggota::factory()->create();

        $response = $this->post('/simpanan-wajib/store', [
            'nilai'      => 50000,
            'periode'    => '2026-07-05',
            'id_anggota' => $anggota->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('master_simpanan_wajib', [
            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $anggota->id,
        ]);
    }
    /** @test */
   public function update_simpanan_sukarela_wajib_status()
    {
         $anggota = Anggota::factory()->create();

        $master = MasterSimpananWajib::factory()->create([
            'id_anggota' => $anggota->id,
            'status'     => 'pending',
        ]);

        $response = $this->put('/simpanan-wajib/' . $master->id, [
            'status' => 'selesai',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('master_simpanan_wajib', [
            'id'     => $master->id,
            'status' => 'selesai',
        ]);
    }

    /** @test */
   public function jika_disetujui_masuk_ke_tabel_final()
    {
        $anggota = Anggota::factory()->create();

        $master = MasterSimpananWajib::factory()->create([
            'id_anggota' => $anggota->id,
            'status'     => 'selesai',
        ]);

        $this->put('/simpanan-wajib/' . $master->id, [
            'status' => 'selesai',
        ]);

        $this->assertDatabaseHas('simpanan_wajib', [
            'nilai'      => $master->nilai,
            'id_anggota' => $master->id_anggota,
        ]);
    }

}