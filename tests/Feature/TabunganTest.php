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
}
