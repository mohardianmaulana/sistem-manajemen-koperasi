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

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post('/simpanan/store', [
            'nilai'   => 100000,
            'tanggal' => '2026-07-04',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungan', [
            'nilai'      => 100000,
            'id_anggota' => $user->id,
        ]);
    }

  public function test_update_tabungan()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $tabungan = SimpananPokok::create([

            'nilai'      => 100000,
            'tanggal'    => '2026-07-04',
            'status'     => 'pending',
            'bukti'      => 'dummy.jpg',
            'id_anggota' => $user->id,

        ]);

        $response = $this->put("/simpanan/updatedata/{$tabungan->id}", [

            'nilai'   => 200000,
            'tanggal' => '2026-07-05',
            'status'  => 'selesai',

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungan', [

            'id'     => $tabungan->id,
            'nilai'  => 200000,
            'status' => 'selesai',

        ]);
    }
}
