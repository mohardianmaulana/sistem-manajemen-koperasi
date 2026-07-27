<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AngsuranTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_persetujuan_akhir_berhasil()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        $role1 = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);
        $user1 = User::factory()->create();
        $user1->assignRole($role1);
        $this->actingAs($user1);

        $response = $this->patch("persetujuan/persetujuanAkhir/{$pengajuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'dokumen_ttd' => UploadedFile::fake()->create(
                'form_pengajuan.pdf',
                100,
                'application/pdf'
            ),
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman', 
        [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'pencairan',
        ]);
    }

    public function test_persetujuan_akhir_gagal()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        $role1 = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);
        $user1 = User::factory()->create();
        $user1->assignRole($role1);
        $this->actingAs($user1);

        $response = $this->patch("persetujuan/persetujuanAkhir/{$pengajuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'dokumen_ttd' => UploadedFile::fake()->create(
                'form_pengajuan.pdf',
                3000,
                'application/pdf'
            ),
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman', 
        [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'menunggu',
        ]);
    }

    public function test_pencairan_atau_create_angsuran_berhasil()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        $role1 = Role::firstOrCreate([
            'name' => 'bendahara',
            'guard_name' => 'web',
        ]);

        $user1 = User::factory()->create();

        $user1->assignRole($role1);

        $this->actingAs($user1);

        $response = $this->patch("persetujuan/pencairan/{$pinjaman->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseCount('angsuran', 18);
        $this->assertDatabaseHas('pinjaman', [
            'id' => $pinjaman->id,
            'status_pinjaman' => 'aktif',
        ]);
    }
}
