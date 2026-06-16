<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PinjamanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_pinjaman_berhasil()
    {
        $pengajuan = Pengajuan::factory()->create();
        $response = $this->post('/create_pinjaman', [
            'id_pengajuan' => $pengajuan->id,
            'tanggal_disetujui' => 2026-05-01,
            'jumlah_disetujui' => 2000000,
            'jumlah_bunga' => 200000,
            'total_pinjaman' => 2200000,
            'status_pinjaman' => 'aktif',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pinjaman', [
            'id_pengajuan' => $pengajuan->id,
            'tanggal_disetujui' => 2026-05-01,
            'jumlah_disetujui' => 2000000,
            'jumlah_bunga' => 200000,
            'total_pinjaman' => 2200000,
            'status_pinjaman' => 'aktif',
        ]);
    }

    public function test_create_pinjaman_gagal()
    {
        $pengajuan = Pengajuan::factory()->create();
        $response = $this->post('/create_pinjaman', [
            'id_pengajuan' => $pengajuan->id,
            'tanggal_disetujui' => 2026-05-01,
            'jumlah_disetujui' => 2000000,
            'jumlah_bunga' => 200000,
            'total_pinjaman' => 2200000,
            'status_pinjaman' => 'aktif',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pinjaman', [
            'id_pengajuan' => $pengajuan->id,
            'tanggal_disetujui' => 2026-05-01,
            'jumlah_disetujui' => 2000000,
            'jumlah_bunga' => 200000,
            'total_pinjaman' => 2200000,
            'status_pinjaman' => 'aktif',
        ]);
    }

    public function test_update_pinjaman()
    {
        $pengajuan = Pengajuan::factory()->create();
        $pinjaman = Pinjaman::factory()->create();
        $response = $this->patch('/create_pinjaman/{$pinjaman->id}', [
            'id_pengajuan' => $pengajuan->id,
            'status_pinjaman' => 'selesai',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pinjaman', [
            'id' => $pinjaman->id,
            'id_pengajuan' => $pengajuan->id,
            'status_pinjaman' => 'aktif',
        ]);
    }
}
