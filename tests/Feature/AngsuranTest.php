<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pinjaman\Database\factories\PengajuanPinjamanFactory;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Tests\TestCase;

class AngsuranTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_create_angsuran_berhasil()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $total_pinjaman = $pengajuan->jumlah_pengajuan + $skema_pinjaman->bunga * $pengajuan->lama_angsuran;
        
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'jumlah_disetujui' => $pengajuan->jumlah_pengajuan,
            'jumlah_bunga' => $skema_pinjaman->bunga * $pengajuan->lama_angsuran,
            'total_pinjaman' => $total_pinjaman,
            'tanggal_disetujui' => '2026-06-01',
            'status_pinjaman' => 'belum_aktif',
        ]);
        $response = $this->patch("persetujuan/pencairan/{$pinjaman->id}");

        $response->assertStatus(302);

        $this->assertDatabaseCount('angsuran', $pengajuan->lama_angsuran);
    }
}
