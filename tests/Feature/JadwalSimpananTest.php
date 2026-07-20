<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Modules\Simpanan\Entities\MasterJenisSimpanan;

use Tests\TestCase;

class JadwalSimpananTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_dapat_melihat_daftar_master_jenis_simpanan()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Membuat data
         */
        MasterJenisSimpanan::factory()->count(3)->create();

        /**
         * Membuka halaman index
         */
        $response = $this->get(
            route('master-jenis-simpanan.index')
        );

        /**
         * Memastikan halaman berhasil ditampilkan
         */
        $response->assertStatus(200);

        $response->assertViewHas('data');
    }

    public function test_admin_dapat_menambah_master_jenis_simpanan()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Menambahkan data
         */
        $response = $this->post(
            route('master-jenis-simpanan.store'),
            [

                'nama_jenis_simpanan' => 'Simpanan Wajib',

                'tanggal_mulai' => '2026-01-01',

                'tanggal_berakhir' => '2026-12-31',

            ]
        );

        /**
         * Memastikan redirect
         */
        $response->assertRedirect(
            route('master-jenis-simpanan.index')
        );

        /**
         * Memastikan data tersimpan
         */
        $this->assertDatabaseHas(
            'master_jenis_simpanan',
            [

                'nama_jenis_simpanan' => 'Simpanan Wajib',

            ]
        );
    }

    public function test_admin_dapat_mengubah_master_jenis_simpanan()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Membuat data
         */
        $jenis = MasterJenisSimpanan::factory()->create();

        /**
         * Mengubah data
         */
        $response = $this->put(
            route(
                'master-jenis-simpanan.update',
                $jenis->id
            ),
            [

                'nama_jenis_simpanan' => 'Simpanan Sukarela',

                'tanggal_mulai' => '2026-01-01',

                'tanggal_berakhir' => '2026-12-31',

            ]
        );

        /**
         * Memastikan redirect
         */
        $response->assertRedirect(
            route('master-jenis-simpanan.index')
        );

        /**
         * Memastikan data berubah
         */
        $this->assertDatabaseHas(
            'master_jenis_simpanan',
            [

                'id' => $jenis->id,

                'nama_jenis_simpanan' => 'Simpanan Sukarela',

            ]
        );
    }

    public function test_gagal_menambah_master_jenis_simpanan_jika_data_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->post(
            route('master-jenis-simpanan.store'),
            []
        );

        $response->assertSessionHasErrors([
            'nama_jenis_simpanan'
        ]);
    }

    public function test_gagal_mengubah_master_jenis_simpanan_jika_data_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $jenis = MasterJenisSimpanan::factory()->create();

        $response = $this->put(
            route(
                'master-jenis-simpanan.update',
                $jenis->id
            ),
            []
        );

        $response->assertSessionHasErrors([
            'nama_jenis_simpanan'
        ]);
    }

    public function test_anggota_tidak_dapat_mengakses_master_jenis_simpanan()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $response = $this->get(
            route('master-jenis-simpanan.index')
        );

        $response->assertForbidden();
    }
}
