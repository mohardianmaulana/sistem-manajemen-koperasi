<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Tests\TestCase;

class MasterSimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
     public function create_simpanan_sukarela_masuk_ke_master()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post('/simpanan-sukarela/store', [

            'nilai'   => 50000,
            'periode' => '2026-07-05',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_sukarela', [

            'nilai'      => 50000,
            'status'     => 'pending',
            'id_anggota' => $user->id,

        ]);
    }

    /** @test */
    public function update_simpanan_sukarela_status()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $master = MasterSimpananSukarela::factory()->create([

            'id_anggota' => $user->id,
            'status'     => 'pending',

        ]);

        $response = $this->put('/simpanan-sukarela/' . $master->id, [

            'status' => 'selesai',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_sukarela', [

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

        $master = MasterSimpananSukarela::factory()->create([

            'id_anggota' => $user->id,
            'status'     => 'pending',

        ]);

        $response = $this->put('/simpanan-sukarela/' . $master->id, [

            'status' => 'selesai',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('simpanan_sukarela', [

            'nilai'      => $master->nilai,
            'id_anggota' => $user->id,

        ]);
    }
}
