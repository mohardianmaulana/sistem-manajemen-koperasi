<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Entities\ShuAnggota;
use Spatie\Permission\Models\Role; 
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PencairanShuTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
     public function test_anggota_dapat_mengajukan_pencairan_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        // User login sebagai anggota
        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        // Data SHU anggota
        $shu = ShuAnggota::factory()->create([
            'id_anggota'     => $anggota->id,
            'shu_simpanan'   => 1000000,
            'shu_pinjaman'   => 500000,
            'pajak'          => 50000,
            'shu_anggota'    => 1450000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(route('pengajuan-pencairan.store'), [
                'id_shu_anggota'    => $shu->id,
                'nominal_pengajuan' => 500000,
            ]);

        $response->assertRedirect(route('pencairan.index'));

        $response->assertSessionHas(
            'success',
            'Pengajuan pencairan SHU berhasil dikirim.'
        );

        $this->assertDatabaseHas('pencairan_shu', [
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 500000,
            'status'            => PencairanShu::STATUS_MENUNGGU,
        ]);
    }

    public function test_anggota_tidak_dapat_mengajukan_pencairan_jika_nominal_melebihi_sisa_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        // User login sebagai anggota
        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        // SHU anggota
        $shu = ShuAnggota::factory()->create([
            'id_anggota'    => $anggota->id,
            'shu_simpanan'  => 1000000,
            'shu_pinjaman'  => 500000,
            'pajak'         => 50000,
            'shu_anggota'   => 1450000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(route('pengajuan-pencairan.store'), [
                'id_shu_anggota'    => $shu->id,
                'nominal_pengajuan' => 2000000, // Melebihi SHU
            ]);

        $response
            ->assertSessionHas(
                'error',
                'Nominal pencairan melebihi sisa SHU yang tersedia.'
            );

        $this->assertDatabaseMissing('pencairan_shu', [
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 2000000,
        ]);
    }

    public function test_anggota_tidak_dapat_mengajukan_pencairan_jika_nominal_kurang_dari_sama_dengan_nol()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        // User login sebagai anggota
        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        // Data SHU anggota
        $shu = ShuAnggota::factory()->create([
            'id_anggota'    => $anggota->id,
            'shu_simpanan'  => 1000000,
            'shu_pinjaman'  => 500000,
            'pajak'         => 50000,
            'shu_anggota'   => 1450000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(route('pengajuan-pencairan.store'), [
                'id_shu_anggota'    => $shu->id,
                'nominal_pengajuan' => 0,
            ]);

        $response->assertSessionHasErrors([
            'nominal_pengajuan'
        ]);

        $this->assertDatabaseMissing('pencairan_shu', [
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 0,
        ]);
    }


    public function test_admin_dapat_menyetujui_pengajuan_pencairan_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Login sebagai admin
        $admin = User::factory()->create();
        $admin->assignRole($role);

        // User anggota pemilik SHU
        $anggota = User::factory()->create();

        // Data SHU
        $shu = ShuAnggota::factory()->create([
            'id_anggota'   => $anggota->id,
            'shu_simpanan' => 1000000,
            'shu_pinjaman' => 500000,
            'pajak'        => 50000,
            'shu_anggota'  => 1450000,
        ]);

        // Pengajuan pencairan
        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 500000,
            'status'            => PencairanShu::STATUS_MENUNGGU,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('pencairan.approve', $pencairan->id));

        $response
            ->assertRedirect(route('pencairan.index'))
            ->assertSessionHas(
                'success',
                'Pengajuan berhasil disetujui.'
            );

        $this->assertDatabaseHas('pencairan_shu', [
            'id' => $pencairan->id,
            'status' => PencairanShu::STATUS_DISETUJUI,
            'disetujui_oleh' => $admin->id,
        ]);

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_persetujuan
        );
    }

    public function test_admin_dapat_menolak_pengajuan_pencairan_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        $anggota = User::factory()->create();

        $shu = ShuAnggota::factory()->create([
            'id_anggota'   => $anggota->id,
            'shu_simpanan' => 1000000,
            'shu_pinjaman' => 500000,
            'pajak'        => 50000,
            'shu_anggota'  => 1450000,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 500000,
            'status'            => PencairanShu::STATUS_MENUNGGU,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('pencairan.reject', $pencairan->id), [
                'keterangan' => 'Nominal tidak sesuai ketentuan.',
            ]);

        $response
            ->assertRedirect(route('pencairan.index'))
            ->assertSessionHas(
                'success',
                'Pengajuan berhasil ditolak.'
            );

        $this->assertDatabaseHas('pencairan_shu', [
                    'id'         => $pencairan->id,
                    'status'     => PencairanShu::STATUS_DITOLAK,
                    'keterangan' => 'Nominal tidak sesuai ketentuan.',
                ]);
    }

   public function test_admin_dapat_mencairkan_pengajuan_pencairan_shu()
    {
        Storage::fake('public');

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        $anggota = User::factory()->create();

        $shu = ShuAnggota::factory()->create([
            'id_anggota'   => $anggota->id,
            'shu_simpanan' => 1000000,
            'shu_pinjaman' => 500000,
            'pajak'        => 50000,
            'shu_anggota'  => 1450000,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota'      => $shu->id,
            'nominal_pengajuan'   => 500000,
            'status'              => PencairanShu::STATUS_DISETUJUI,
            'tanggal_persetujuan' => now(),
            'disetujui_oleh'      => $admin->id,
            'bukti'               => null,
        ]);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this
            ->actingAs($admin)
            ->put(
                route('pencairan.cairkan', $pencairan->id),
                [
                    'bukti' => $file,
                ]
            );

        $response
            ->assertRedirect(route('pencairan.index'))
            ->assertSessionHas(
                'success',
                'SHU berhasil dicairkan.'
            );

        $this->assertDatabaseHas('pencairan_shu', [
            'id'     => $pencairan->id,
            'status' => PencairanShu::STATUS_DICAIRKAN,
        ]);

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_pencairan
        );

        Storage::disk('public')->assertExists(
            $pencairan->fresh()->bukti
        );
    }


    public function test_admin_tidak_dapat_mencairkan_pengajuan_yang_belum_disetujui()
    {
        Storage::fake('public');

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        $anggota = User::factory()->create();

        $shu = ShuAnggota::factory()->create([
            'id_anggota'   => $anggota->id,
            'shu_simpanan' => 1000000,
            'shu_pinjaman' => 500000,
            'pajak'        => 50000,
            'shu_anggota'  => 1450000,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota'    => $shu->id,
            'nominal_pengajuan' => 500000,
            'status'            => PencairanShu::STATUS_MENUNGGU,
        ]);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this
            ->actingAs($admin)
            ->from(route('pencairan.index'))
            ->put(route('pencairan.cairkan', $pencairan->id), [
                'bukti' => $file,
            ]);

        $response
            ->assertRedirect(route('pencairan.index'))
            ->assertSessionHas(
                'error',
                'Pengajuan belum disetujui atau sudah dicairkan.'
            );

        $this->assertDatabaseHas('pencairan_shu', [
            'id' => $pencairan->id,
            'status' => PencairanShu::STATUS_MENUNGGU,
        ]);
    }

    public function test_anggota_tidak_dapat_menyetujui_pengajuan_pencairan_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        $shu = ShuAnggota::factory()->create([
            'id_anggota' => $anggota->id,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_MENUNGGU,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->put(route('pencairan.approve', $pencairan->id));

        $response->assertStatus(403);
    }

    public function test_anggota_tidak_dapat_menolak_pengajuan_pencairan_shu()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        $shu = ShuAnggota::factory()->create([
            'id_anggota' => $anggota->id,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_MENUNGGU,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->put(route('pencairan.reject', $pencairan->id), [
                'keterangan' => 'Ditolak',
            ]);

        $response->assertStatus(403);
    }

    public function test_anggota_tidak_dapat_mencairkan_pengajuan_pencairan_shu()
    {
        Storage::fake('public');

        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $anggota = User::factory()->create();
        $anggota->assignRole($role);

        $shu = ShuAnggota::factory()->create([
            'id_anggota' => $anggota->id,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_DISETUJUI,
        ]);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this
            ->actingAs($anggota)
            ->put(route('pencairan.cairkan', $pencairan->id), [
                'bukti' => $file,
            ]);

        $response->assertStatus(403);
    }

    public function test_guest_tidak_dapat_mengakses_halaman_pencairan()
    {
        $response = $this->get(route('pencairan.index'));

        $response->assertRedirect('/login');
    }
}
