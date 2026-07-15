<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
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
        /**
         * Membuat role
         */
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $anggotaRole = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $admin = User::factory()->create();

        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        /**
         * Membuat anggota
         */
        $anggota1 = User::factory()->create();
        $anggota1->assignRole($anggotaRole);

        $anggota2 = User::factory()->create();
        $anggota2->assignRole($anggotaRole);

        /**
         * Menetapkan simpanan wajib
         */
        $response = $this->post('/simpanan-wajib/store', [

            'nilai'   => 50000,
            'periode' => '2026-07-05',

        ]);

        $response->assertRedirect();

        /**
         * Anggota pertama mendapatkan simpanan wajib
         */
        $this->assertDatabaseHas('master_simpanan_wajib', [

            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $anggota1->id,

        ]);

        /**
         * Anggota kedua mendapatkan simpanan wajib
         */
        $this->assertDatabaseHas('master_simpanan_wajib', [

            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $anggota2->id,

        ]);
    }

    /** @test */
    public function test_update_simpanan_wajib_status()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $master = MasterSimpananWajib::factory()->create([

            'id_anggota' => $user->id,
            'status'     => 'pending',

        ]);

        $response = $this->put('/simpanan-wajib/' . $master->id, [

            'status' => 'selesai',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_wajib', [

            'id'     => $master->id,
            'status' => 'selesai',

        ]);
    }

    /** @test */
    public function test_jika_disetujui_masuk_ke_tabel_final()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $master = MasterSimpananWajib::factory()->create([

            'id_anggota' => $user->id,
            'status'     => 'pending',

        ]);

        $this->put('/simpanan-wajib/' . $master->id, [

            'status' => 'selesai',

        ]);

        $this->assertDatabaseHas('simpanan_wajib', [

            'nilai'      => $master->nilai,
            'id_anggota' => $user->id,

        ]);
    }

    public function test_admin_gagal_membuat_simpanan_wajib_jika_nilai_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post('/simpanan-wajib/store', [

            'nilai' => '',
            'periode' => '2026-07-05',

        ]);

        $response->assertSessionHasErrors('nilai');

        $this->assertDatabaseCount('master_simpanan_wajib', 0);
    }

    public function test_admin_gagal_membuat_simpanan_wajib_jika_periode_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post('/simpanan-wajib/store', [

            'nilai' => 50000,
            'periode' => '',

        ]);

        $response->assertSessionHasErrors('periode');
    }

    public function test_guest_tidak_dapat_membuat_simpanan_wajib()
    {
        $response = $this->post('/simpanan-wajib/store', [

            'nilai' => 50000,
            'periode' => '2026-07-05',

        ]);

        $response->assertRedirect('/login');
    }

    public function test_admin_gagal_mengubah_status_jika_status_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $master = MasterSimpananWajib::factory()->create([

            'id_anggota' => $user->id,
            'status' => 'pending',

        ]);

        $response = $this->put('/simpanan-wajib/' . $master->id, [

            'status' => '',

        ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('master_simpanan_wajib', [

            'id' => $master->id,
            'status' => 'pending',

        ]);
    }

    public function test_guest_tidak_dapat_mengubah_status_simpanan_wajib()
    {
        $user = User::factory()->create();

        $master = MasterSimpananWajib::factory()->create([

            'id_anggota' => $user->id,
            'status' => 'pending',

        ]);

        $response = $this->put('/simpanan-wajib/' . $master->id, [

            'status' => 'selesai',

        ]);

        $response->assertRedirect('/login');
    }
 
    public function test_data_master_tetap_tersimpan_setelah_disetujui()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $master = MasterSimpananWajib::factory()->create([

            'id_anggota' => $user->id,
            'status' => 'pending',

        ]);

        $this->put('/simpanan-wajib/' . $master->id, [

            'status' => 'selesai',

        ]);

        $this->assertDatabaseHas('master_simpanan_wajib', [

            'id' => $master->id,
            'status' => 'selesai',

        ]);

        $this->assertDatabaseHas('simpanan_wajib', [

            'nilai' => $master->nilai,
            'id_anggota' => $user->id,

        ]);
    }
    public function test_admin_dapat_membuka_halaman_simpanan_wajib()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->get(route('simpanan-wajib.index'));

        $response->assertStatus(200);
    }
}