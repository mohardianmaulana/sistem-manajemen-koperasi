<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Persetujuan;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PersetujuanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'bendahara']);
        Role::create(['name' => 'wadir']);
        Role::create(['name' => 'ketua']);
    }

    public function test_update_persetujuan_bendahara_disetujui()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole('bendahara');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
            'disetujui_oleh' => $user->id,
            'status' => 'disetujui',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => null,
        ]);

        $this->assertDatabaseHas('persetujuan', [
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
        ]);
    }

    public function test_update_persetujuan_bendahara_ditolak()
    {
        $user = User::factory()->create();
        $user->assignRole('bendahara');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);
        $response = $this->put("persetujuan/tolak/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'catatan' => 'tidak ada',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
            'disetujui_oleh' => $user->id,
            'status' => 'ditolak',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => 'tidak ada',
        ]);
    }

    public function test_update_persetujuan_bendahara_ditolak_bukan_bendahara()
    {
        $user = User::factory()->create();
        $user->assignRole('wadir');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
            'disetujui_oleh' => null,
            'status' => 'menunggu',
            'tanggal_disetujui' => null,
            'catatan' => null,
        ]);
    }

    public function test_update_persetujuan_wadir_disetujui()
    {
        $user = User::factory()->create();
        $user->assignRole('wadir');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
            'disetujui_oleh' => $user->id,
            'status' => 'disetujui',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => null,
        ]);

        $this->assertDatabaseHas('persetujuan', [
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
        ]);
    }

    public function test_update_persetujuan_wadir_ditolak()
    {
        $user = User::factory()->create();
        $user->assignRole('wadir');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
        ]);
        $response = $this->put("persetujuan/tolak/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'catatan' => 'tidak ada',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
            'disetujui_oleh' => $user->id,
            'status' => 'ditolak',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => 'tidak ada',
        ]);
    }

    public function test_update_persetujuan_wadir_ditolak_bukan_wadir()
    {
        $user = User::factory()->create();
        $user->assignRole('bendahara');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'wadir',
            'disetujui_oleh' => null,
            'status' => 'menunggu',
            'tanggal_disetujui' => null,
            'catatan' => null,
        ]);
    }

    public function test_update_persetujuan_ketua_disetujui()
    {
        $user = User::factory()->create();
        $user->assignRole('ketua');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
            'disetujui_oleh' => $user->id,
            'status' => 'disetujui',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => null,
        ]);

        $this->assertDatabaseHas('pinjaman', 
        [
            'id_pengajuan' => $pengajuan->id,
        ]);
    }

    public function test_update_persetujuan_ketua_ditolak()
    {
        $user = User::factory()->create();
        $user->assignRole('ketua');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
        ]);
        $response = $this->put("persetujuan/tolak/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
            'catatan' => 'tidak ada',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
            'disetujui_oleh' => $user->id,
            'status' => 'ditolak',
            'tanggal_disetujui' => now()->toDateString(),
            'catatan' => 'tidak ada',
        ]);
    }

    public function test_update_persetujuan_ketua_ditolak_bukan_ketua()
    {
        $user = User::factory()->create();
        $user->assignRole('wadir');
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $persetujuan = Persetujuan::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
        ]);
        $response = $this->put("persetujuan/setujui/{$persetujuan->id}", [
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('persetujuan', [
            'id' => $persetujuan->id,
            'id_pengajuan' => $pengajuan->id,
            'role' => 'ketua',
            'disetujui_oleh' => null,
            'status' => 'menunggu',
            'tanggal_disetujui' => null,
            'catatan' => null,
        ]);
    }

    public function test_full_flow_pengajuan_sampai_pinjaman()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}")->assertStatus(302);

        // step awal: generate bendahara
        $this->patch("pengajuan_pinjaman/teruskan/{$pengajuan->id}")->assertStatus(302);

        // bendahara
        $bendahara = Persetujuan::where('role', 'bendahara')->where('id_pengajuan', $pengajuan->id)->first();
        $this->assertNotNull($bendahara);

        $bendaharaUser = User::factory()->create();
        $bendaharaUser->assignRole('bendahara');
        $this->actingAs($bendaharaUser);

        $this->put("persetujuan/setujui/{$bendahara->id}", [
            'id_pengajuan'      => $pengajuan->id,
        ])->assertStatus(302);

        // wadir
        $wadir = Persetujuan::where('role', 'wadir')->where('id_pengajuan', $pengajuan->id)->first();
        $this->assertNotNull($wadir);

        $wadirUser = User::factory()->create();
        $wadirUser->assignRole('wadir');
        $this->actingAs($wadirUser);

        $this->put("persetujuan/setujui/{$wadir->id}", [
            'id_pengajuan'      => $pengajuan->id,
        ])->assertStatus(302);

        // ketua
        $ketua = Persetujuan::where('role', 'ketua')->where('id_pengajuan', $pengajuan->id)->first();
        $this->assertNotNull($ketua);

        $ketuaUser = User::factory()->create();
        $ketuaUser->assignRole('ketua');
        $this->actingAs($ketuaUser);

        $this->put("persetujuan/setujui/{$ketua->id}", [
            'id_pengajuan'      => $pengajuan->id,
        ])->assertStatus(302);

        // hasil akhir
        $this->assertDatabaseCount('pinjaman', 1);

        $this->assertDatabaseHas('pinjaman', 
        [
            'id_pengajuan' => $pengajuan->id,
        ]);
    }
}
