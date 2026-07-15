<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // User yang login sebagai admin
        $admin = User::factory()->create();
        $admin->assignRole($role);

        // User yang dipilih pada dropdown
        $anggota = User::factory()->create();

        $this->actingAs($admin);

        $response = $this->post('/simpanan/store', [
            'id_anggota' => $anggota->id,
            'nilai'      => 100000,
            'tanggal'    => '2026-07-04',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungan', [
            'id_anggota' => $anggota->id,
            'nilai'      => 100000,
            'status'     => 'pending',
        ]);
    }

  public function test_update_tabungan()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Admin yang login
        $admin = User::factory()->create();
        $admin->assignRole($role);

        // Anggota pemilik tabungan
        $anggota = User::factory()->create();

        $this->actingAs($admin);

        $tabungan = SimpananPokok::create([
            'nilai'      => 100000,
            'tanggal'    => '2026-07-04',
            'status'     => 'pending',
            'bukti'      => 'dummy.jpg',
            'id_anggota' => $anggota->id,
        ]);

        $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [
            'nilai'   => 200000,
            'tanggal' => '2026-07-05',
            'status'  => 'selesai',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungan', [
            'id'         => $tabungan->id,
            'id_anggota' => $anggota->id,
            'nilai'      => 200000,
            'status'     => 'selesai',
        ]);
    }

    public function test_anggota_tidak_bisa_membuka_halaman_create()
        {
            $role = Role::firstOrCreate([
                'name' => 'anggota',
                'guard_name' => 'web',
            ]);

            $user = User::factory()->create();

            $user->assignRole($role);

            $this->actingAs($user);

            $response = $this->get(route('simpanan-pokok.create'));

            $response->assertRedirect(route('simpanan-pokok.index'));

            $response->assertSessionHas(
                'error',
                'Anda tidak memiliki hak akses untuk mengakses halaman ini.'
            );
        }
        public function test_admin_gagal_menambah_tabungan_jika_nilai_kosong()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $anggota = User::factory()->create();

            $this->actingAs($admin);

            $response = $this->post('/simpanan/store', [
                'id_anggota' => $anggota->id,
                'nilai'      => '',
                'tanggal'    => '2026-07-04',
            ]);

            $response->assertSessionHasErrors('nilai');

            $this->assertDatabaseMissing('tabungan', [
                'id_anggota' => $anggota->id,
                'tanggal'    => '2026-07-04',
            ]);
        }

        public function test_admin_gagal_menambah_tabungan_jika_tanggal_kosong()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $anggota = User::factory()->create();

            $this->actingAs($admin);

            $response = $this->post('/simpanan/store', [
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
                'tanggal'    => '',
            ]);

            $response->assertSessionHasErrors('tanggal');

            $this->assertDatabaseMissing('tabungan', [
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
            ]);
        }

        public function test_admin_gagal_menambah_tabungan_jika_anggota_kosong()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $this->actingAs($admin);

            $response = $this->post('/simpanan/store', [
                'id_anggota' => '',
                'nilai'      => 100000,
                'tanggal'    => '2026-07-04',
            ]);

            $response->assertSessionHasErrors('id_anggota');

            $this->assertDatabaseCount('tabungan', 0);
        }

        public function test_guest_tidak_dapat_menambah_tabungan()
        {
            $anggota = User::factory()->create();

            $response = $this->post('/simpanan/store', [
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
                'tanggal'    => '2026-07-04',
            ]);

            $response->assertRedirect('/login');
        }

        public function test_admin_dapat_membuka_halaman_create()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $this->actingAs($admin);

            $response = $this->get(route('simpanan-pokok.create'));

            $response->assertStatus(200);
        }

        public function test_guest_tidak_dapat_membuka_halaman_create()
        {
            $response = $this->get(route('simpanan-pokok.create'));

            $response->assertRedirect('/login');
        }

        public function test_admin_gagal_update_tabungan_jika_nilai_kosong()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $anggota = User::factory()->create();

            $this->actingAs($admin);

            $tabungan = SimpananPokok::create([
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
                'tanggal'    => '2026-07-04',
                'status'     => 'pending',
            ]);

            $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [
                'nilai'   => '',
                'tanggal' => '2026-07-05',
                'status'  => 'selesai',
            ]);

            $response->assertSessionHasErrors('nilai');
        }

        public function test_admin_gagal_update_tabungan_jika_status_kosong()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $anggota = User::factory()->create();

            $this->actingAs($admin);

            $tabungan = SimpananPokok::create([
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
                'tanggal'    => '2026-07-04',
                'status'     => 'pending',
            ]);

            $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [
                'nilai'   => 100000,
                'tanggal' => '2026-07-05',
                'status'  => '',
            ]);

            $response->assertSessionHasErrors('status');
        }

        public function test_guest_tidak_dapat_update_tabungan()
        {
            $anggota = User::factory()->create();

            $tabungan = SimpananPokok::create([
                'id_anggota' => $anggota->id,
                'nilai'      => 100000,
                'tanggal'    => '2026-07-04',
                'status'     => 'pending',
            ]);

            $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [
                'nilai'   => 200000,
                'tanggal' => '2026-07-05',
                'status'  => 'selesai',
            ]);

            $response->assertRedirect('/login');
        }

        public function test_admin_gagal_update_tabungan_yang_tidak_ditemukan()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin = User::factory()->create();
            $admin->assignRole($role);

            $this->actingAs($admin);

            $response = $this->put('/simpanan/updatedata/99999', [
                'nilai'   => 100000,
                'tanggal' => '2026-07-05',
                'status'  => 'selesai',
            ]);

            $response->assertStatus(404);
        }
}
