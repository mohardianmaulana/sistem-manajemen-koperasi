<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pinjaman\Entities\Anggota;
use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Tests\TestCase;

class MasterSimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
      public function create_simpanan_sukarela_masuk_ke_master()
    {
        $anggota = Anggota::factory()->create();

        $response = $this->post('/simpanan-sukarela/store', [
            'nilai'      => 50000,
            'periode'    => '2026-07-05',
            'id_anggota' => $anggota->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $anggota->id,
        ]);
    }

    /** @test */
   public function update_simpanan_sukarela_wajib_status()
    {
        $anggota = Anggota::factory()->create();

        $master = MasterSimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'status'     => 'pending',
        ]);

       $response = $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => 'selesai',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'id'     => $master->id,
            'status' => 'selesai',
        ]);
    }

    /** @test */
   public function jika_disetujui_masuk_ke_tabel_final()
    {
        $anggota = Anggota::factory()->create();

        $master = MasterSimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'status'     => 'selesai',
        ]);

        $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => 'selesai',
        ]);

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'nilai'      => $master->nilai,
            'id_anggota' => $master->id_anggota,
        ]);
    }
}
