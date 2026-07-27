<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pinjaman\Entities\Jaminan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JaminanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_create_jaminan_sukses()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $response = $this->post("jaminan/store", 
            [
                'nama' => 'Surat tanah',
                'deskripsi' => 'Ini surat tanah',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'nama' => 'Surat tanah',
            'deskripsi' => 'Ini surat tanah',
        ]);
    }

    public function test_create_jaminan_gagal_nama_atau_deskripsi_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $response = $this->post("jaminan/store", 
            [
                'nama' => null,
                'deskripsi' => null,
            ]
        );

        $response->assertSessionHasErrors('nama', 'deskripsi');

        $this->assertDatabaseCount('jaminan', 0);
    }

    public function test_update_jaminan_sukses()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $response = $this->put("jaminan/update/{$jaminan->id}", 
            [
                'nama' => 'Sertifikat rumah',
                'deskripsi' => 'Ini sertifikat rumah',
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'id' => $jaminan->id,
            'nama' => 'Sertifikat rumah',
        ]);
    }

    public function test_update_jaminan_gagal_nama_atau_deskripsi_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $response = $this->put("jaminan/update/{$jaminan->id}", 
            [
                'nama' => null,
                'deskripsi' => null,
            ]
        );

        $response->assertSessionHasErrors('nama', 'deskripsi');

        $this->assertDatabaseHas('jaminan', 
        [
            'nama' => 'Surat tanah',
            'deskripsi' => 'Ini surat tanah',
        ]);
    }

    public function test_arsip_jaminan_sukses()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $response = $this->patch("jaminan/nonaktif/{$jaminan->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'id' => $jaminan->id,
            'nama' => 'Surat tanah',
            'status' => 'nonaktif',
        ]);
    }

    public function test_aktifkan_jaminan_sukses()
    {
        $role = Role::firstOrCreate([
            'name' => 'koordinator',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $response = $this->patch("jaminan/aktif/{$jaminan->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('jaminan', 
        [
            'id' => $jaminan->id,
            'nama' => 'Surat tanah',
            'status' => 'aktif',
        ]);
    }
}
