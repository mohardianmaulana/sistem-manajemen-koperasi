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
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post('/simpanan-wajib/store', [

            'nilai'   => 50000,
            'periode' => '2026-07-05',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_wajib', [

            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $user->id,

        ]);
    }

    /** @test */
    public function update_simpanan_wajib_status()
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
    public function jika_disetujui_masuk_ke_tabel_final()
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

}