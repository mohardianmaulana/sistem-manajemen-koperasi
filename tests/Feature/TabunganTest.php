<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pinjaman\Entities\Anggota;
use Tests\TestCase;
use Modules\Simpanan\Entities\SimpananPokok;

class TabunganTest extends TestCase
{
    use refreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
   public function test_create_tabungan()
    {
        $anggota = Anggota::factory()->create();

        $response = $this->post('/simpanan/store', [
            'nilai' => 100000,
            'tanggal' => '2026-07-04',
            'id_anggota' => $anggota->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('tabungan', [
            'nilai' => 100000,
            'id_anggota' => $anggota->id,
        ]);
    }

  public function test_update_tabungan()
    {
        $anggota = Anggota::factory()->create();

        $tabungan = SimpananPokok::create([
            'nilai' => 100000,
            'tanggal' => '2026-07-04',
            'status' => 'pending',
            'bukti' => 'dummy.jpg',
            'id_anggota' => $anggota->id, // 🔥 FIX
        ]);

        $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [
            'nilai' => 200000,
            'tanggal' => '2026-07-05',
            'status' => 'selesai',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('tabungan', [
            'id' => $tabungan->id,
            'nilai' => 200000,
            'status' => 'selesai',
        ]);
    }
}
