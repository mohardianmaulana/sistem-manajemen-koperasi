<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\SHU\Entities\ShuKoperasi;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SisaHasilUsahaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
   /** @test */
    public function test_admin_dapat_membuat_shu_koperasi()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Simpan data SHU
         */
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 1000000,

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Berhasil redirect
         */
        $response->assertRedirect();

        /**
         * Data berhasil tersimpan
         */
        $this->assertDatabaseHas(
            'sisa_hasil_usaha',
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 1000000,

                'jasa_simpanan' => 400000,

                'jasa_pinjaman' => 200000,

                'dana_cadangan' => 200000,

                'jasa_pengurus' => 100000,

                'dana_sosial' => 100000,

            ]
        );
    }
    public function test_sistem_menghitung_nominal_shu_berdasarkan_persentase()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Simpan SHU
         */
        $this->post(route('shu-koperasi.store'), [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'total_shu' => 1000000,

            'persen_jasa_simpanan' => 40,

            'persen_jasa_pinjaman' => 20,

            'persen_dana_cadangan' => 20,

            'persen_jasa_pengurus' => 10,

            'persen_dana_sosial' => 10,

        ]);

        /**
         * Memastikan nominal dihitung sesuai persentase
         */
        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'jasa_simpanan' => 400000,

            'jasa_pinjaman' => 200000,

            'dana_cadangan' => 200000,

            'jasa_pengurus' => 100000,

            'dana_sosial' => 100000,

            'total_shu' => 1000000,

        ]);
    }

    public function test_admin_gagal_membuat_shu_jika_periode_awal_kosong()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa periode awal
         */
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 1000000,

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('periode_awal');
    }
    public function test_admin_gagal_membuat_shu_jika_periode_akhir_kosong()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa periode akhir
         */
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '',

                'total_shu' => 1000000,

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('periode_akhir');
    }

    public function test_admin_gagal_membuat_shu_jika_total_shu_kosong()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa total SHU
         */
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => '',

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('total_shu');
    }

    public function test_admin_gagal_membuat_shu_jika_total_persentase_tidak_100_persen()
    {
        /**
         * Membuat role admin
         */
        $role = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Total persentase hanya 90%
         */
        $response = $this->post(route('shu-koperasi.store'), [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'total_shu' => 1000000,

            'persen_jasa_simpanan' => 30,

            'persen_jasa_pinjaman' => 20,

            'persen_dana_cadangan' => 20,

            'persen_jasa_pengurus' => 10,

            'persen_dana_sosial' => 10,

        ]);

        /**
         * Sistem mengembalikan ke halaman sebelumnya
         */
        $response->assertRedirect();

        /**
         * Muncul pesan error
         */
        $response->assertSessionHasErrors([
            'persentase' => 'Total persentase SHU harus tepat 100%.',
        ]);

        /**
         * Data tidak boleh tersimpan
         */
        $this->assertDatabaseMissing('sisa_hasil_usaha', [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

        ]);
    }

    public function test_guest_tidak_dapat_membuat_shu_koperasi()
    {
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 1000000,

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Harus diarahkan ke halaman login
         */
        $response->assertRedirect(route('login.show'));

        /**
         * Memastikan data tidak tersimpan
         */
        $this->assertDatabaseMissing(
            'sisa_hasil_usaha',
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

            ]
        );
    }

 
    public function test_guest_tidak_dapat_memperbarui_shu_koperasi()
    {
        /**
         * Membuat data SHU
         */
        $shu = ShuKoperasi::factory()->create([

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'total_shu' => 1000000,

            'jasa_simpanan' => 400000,

            'jasa_pinjaman' => 200000,

            'dana_cadangan' => 200000,

            'jasa_pengurus' => 100000,

            'dana_sosial' => 100000,

        ]);

        /**
         * Guest mencoba mengubah data SHU
         */
        $response = $this->put(
            route('shu-koperasi.update', $shu->id),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 2000000,

                'persen_jasa_simpanan' => 35,

                'persen_jasa_pinjaman' => 25,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Guest harus diarahkan ke halaman login
         */
        $response->assertRedirect(route('login.show'));

        /**
         * Memastikan data tidak berubah
         */
        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'id' => $shu->id,

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'total_shu' => 1000000,

            'jasa_simpanan' => 400000,

            'jasa_pinjaman' => 200000,

            'dana_cadangan' => 200000,

            'jasa_pengurus' => 100000,

            'dana_sosial' => 100000,

        ]);
    }

    public function test_admin_dapat_membuka_halaman_tambah_shu_koperasi()
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
         * Membuka halaman create
         */
        $response = $this->get(route('shu-koperasi.create'));

        /**
         * Halaman berhasil dibuka
         */
        $response->assertStatus(200);

        /**
         * View yang digunakan benar
         */
        $response->assertViewIs('shu::shukoperasi.create');
    }

    public function test_admin_dapat_membuka_halaman_edit_shu_koperasi()
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
         * Membuat data SHU
         */
        $shu = ShuKoperasi::factory()->create();

        /**
         * Membuka halaman edit
         */
        $response = $this->get(
            route('shu-koperasi.show', $shu->id)
        );

        /**
         * Halaman berhasil dibuka
         */
        $response->assertStatus(200);

        /**
         * View yang digunakan benar
         */
        $response->assertViewIs('shu::shukoperasi.edit');
    }

    public function test_anggota_tidak_dapat_membuka_halaman_shu_koperasi()
    {
        /**
         * Membuat role anggota
         */
        $role = Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai anggota
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Membuka halaman SHU koperasi
         */
        $response = $this->get(
            route('shu-koperasi.index')
        );

        /**
         * Tidak boleh mengakses halaman
         */
        $response->assertStatus(403);
    }

    public function test_anggota_tidak_dapat_membuka_halaman_tambah_shu_koperasi()
    {
        /**
         * Membuat role anggota
         */
        $role = Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai anggota
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Membuka halaman tambah SHU
         */
        $response = $this->get(
            route('shu-koperasi.create')
        );

        /**
         * Tidak boleh mengakses halaman
         */
        $response->assertStatus(403);
    }

    public function test_anggota_tidak_dapat_menyimpan_shu_koperasi()
    {
        /**
         * Membuat role anggota
         */
        $role = Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai anggota
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Mencoba menyimpan SHU
         */
        $response = $this->post(
            route('shu-koperasi.store'),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 1000000,

                'persen_jasa_simpanan' => 40,

                'persen_jasa_pinjaman' => 20,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Tidak boleh mengakses
         */
        $response->assertStatus(403);

        /**
         * Memastikan data tidak tersimpan
         */
        $this->assertDatabaseMissing(
            'sisa_hasil_usaha',
            [
                'periode_awal' => '2026-01-01',
                'periode_akhir' => '2026-12-31',
            ]
        );
    }

    public function test_anggota_tidak_dapat_memperbarui_shu_koperasi()
    {
        /**
         * Membuat role anggota
         */
        $role = Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai anggota
         */
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Membuat data SHU
         */
        $shu = ShuKoperasi::factory()->create();

        /**
         * Mencoba mengubah SHU
         */
        $response = $this->put(
            route('shu-koperasi.update', $shu->id),
            [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'total_shu' => 2000000,

                'persen_jasa_simpanan' => 35,

                'persen_jasa_pinjaman' => 25,

                'persen_dana_cadangan' => 20,

                'persen_jasa_pengurus' => 10,

                'persen_dana_sosial' => 10,

            ]
        );

        /**
         * Tidak boleh mengakses
         */
        $response->assertStatus(403);

        /**
         * Memastikan data tetap
         */
        $this->assertDatabaseHas(
            'sisa_hasil_usaha',
            [
                'id' => $shu->id,
                'total_shu' => 1000000,
            ]
        );
    }
}