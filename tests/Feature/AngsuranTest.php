<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_pencairan_atau_create_angsuran_berhasil()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response = $this->patch("persetujuan/pencairan/{$pinjaman->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseCount('angsuran', 18);
    }
}
