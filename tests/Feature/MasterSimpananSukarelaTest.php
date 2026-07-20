<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Modules\Simpanan\Entities\MasterSimpananSukarela;
use Modules\Simpanan\Entities\MasterJenisSimpanan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasterSimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_simpanan_sukarela_masuk_ke_master()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Buat jadwal Simpanan Sukarela yang aktif
         */
        MasterJenisSimpanan::factory()
            ->aktif()
            ->create([
                'nama_jenis_simpanan' => 'Simpanan Sukarela',
            ]);

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
    public function test_update_simpanan_sukarela_status()
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
    public function test_jika_disetujui_masuk_ke_tabel_final()
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

   public function test_anggota_gagal_menambah_simpanan_sukarela_jika_nilai_kosong()
    {
        $role = Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user);

        MasterJenisSimpanan::factory()
            ->aktif()
            ->create([
                'nama_jenis_simpanan' => 'Simpanan Sukarela',
            ]);

        $response = $this->post('/simpanan-sukarela/store', [
            'nilai'   => '',
            'periode' => '2026-07-05',
        ]);

        $response->assertSessionHasErrors('nilai');

        $this->assertDatabaseCount('master_simpanan_sukarela', 0);
    }

    public function test_guest_tidak_dapat_membuat_simpanan_sukarela()
    {
        $response = $this->post('/simpanan-sukarela/store', [
            'nilai'   => 50000,
            'periode' => '2026-07-05',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_admin_gagal_update_status_jika_status_kosong()
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
        ]);

        $response = $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => '',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_jika_status_ditolak_maka_tidak_masuk_ke_tabel_final()
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
            'status' => 'pending',
        ]);

        $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => 'tidak berhasil',
        ]);

        $this->assertDatabaseMissing('simpanan_sukarela', [
            'id_anggota' => $user->id,
            'nilai' => $master->nilai,
        ]);
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

        $master = MasterSimpananSukarela::factory()->create([
            'id_anggota' => $user->id,
            'status'     => 'pending',
        ]);

        $response = $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => '',
        ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'id'     => $master->id,
            'status' => 'pending',
        ]);
    }

    public function test_guest_tidak_dapat_mengubah_status_simpanan_sukarela()
    {
        $master = MasterSimpananSukarela::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->put('/simpanan-sukarela/' . $master->id, [
            'status' => 'selesai',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_jika_status_tidak_berhasil_maka_tidak_masuk_ke_tabel_final()
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
            'status' => 'tidak berhasil',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseMissing('simpanan_sukarela', [
            'id_anggota' => $user->id,
            'nilai'      => $master->nilai,
        ]);
    }

        public function test_admin_dapat_membuka_halaman_persetujuan_simpanan_sukarela()
        {
            $role = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $user = User::factory()->create();

            $user->assignRole($role);

            $this->actingAs($user);

            $response = $this->get(route('simpanan-sukarela.index'));

            $response->assertStatus(200);
        }

        public function test_anggota_tidak_dapat_membuat_simpanan_jika_jadwal_tidak_aktif()
        {
            $role = Role::firstOrCreate([
                'name' => 'anggota',
                'guard_name' => 'web',
            ]);

            $user = User::factory()->create();

            $user->assignRole($role);

            $this->actingAs($user);

            MasterJenisSimpanan::factory()
                ->tidakAktif()
                ->create([
                    'nama_jenis_simpanan' => 'Simpanan Sukarela',
                ]);

            $response = $this->post('/simpanan-sukarela/store', [

                'nilai'   => 50000,
                'periode' => '2026-07-05',

            ]);

            $response->assertSessionHas(
                'error',
                'Jadwal Simpanan Sukarela sedang tidak aktif.'
            );

            $this->assertDatabaseMissing('master_simpanan_sukarela', [

                'nilai' => 50000,

            ]);
        }

        public function test_anggota_tidak_dapat_upload_bukti_jika_status_bukan_tidak_berhasil()
        {
            Storage::fake('public');

            $role = Role::firstOrCreate([
                'name' => 'anggota',
                'guard_name' => 'web',
            ]);

            $user = User::factory()->create();

            $user->assignRole($role);

            $this->actingAs($user);

            MasterJenisSimpanan::factory()
                ->aktif()
                ->create([
                    'nama_jenis_simpanan' => 'Simpanan Sukarela',
                ]);

            $master = MasterSimpananSukarela::factory()->create([

                'id_anggota' => $user->id,
                'status' => 'pending',

            ]);

            $response = $this->put('/simpanan-sukarela/'.$master->id, [

                'status' => 'pending',
                'bukti' => UploadedFile::fake()->image('bukti.jpg'),

            ]);

            $response->assertSessionHas(
                'error',
                'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
            );
        }

        public function test_anggota_tidak_dapat_upload_bukti_jika_jadwal_tidak_aktif()
        {
            Storage::fake('public');

            $role = Role::firstOrCreate([
                'name' => 'anggota',
                'guard_name' => 'web',
            ]);

            $user = User::factory()->create();

            $user->assignRole($role);

            $this->actingAs($user);

            MasterJenisSimpanan::factory()
                ->tidakAktif()
                ->create([
                    'nama_jenis_simpanan' => 'Simpanan Sukarela',
                ]);

            $master = MasterSimpananSukarela::factory()->create([

                'id_anggota' => $user->id,
                'status' => 'tidak berhasil',

            ]);

            $response = $this->put('/simpanan-sukarela/'.$master->id, [

                'status' => 'tidak berhasil',
                'bukti' => UploadedFile::fake()->image('bukti.jpg'),

            ]);

            $response->assertSessionHas(
                'error',
                'Jadwal Simpanan Sukarela sedang tidak aktif.'
            );
        }

}
