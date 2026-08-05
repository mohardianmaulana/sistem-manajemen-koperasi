<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Rat\Entities\Rat;
use Spatie\Permission\Models\Role; 
use Tests\TestCase;

class RatTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_membuka_halaman_tambah_rat()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->get(route('rat.create'));

        $response
            ->assertStatus(200)
            ->assertViewIs('rat::create');
    }

    public function test_admin_dapat_menambah_rat()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('rat.store'), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-20',
                'status' => Rat::STATUS_BELUM,
            ]);

        $response
            ->assertRedirect(route('rat.index'));

        $response
            ->assertSessionHas(
                'success',
                'Data RAT berhasil ditambahkan.'
            );

        $this->assertDatabaseHas('rat', [
            'tahun' => 2026,
            'status' => Rat::STATUS_BELUM,
        ]);
    }

    public function test_gagal_menambah_rat_jika_tahun_sudah_ada()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        Rat::factory()->create([
            'tahun' => 2026,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('rat.store'), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-20',
                'status' => Rat::STATUS_BELUM,
            ]);

        $response
            ->assertSessionHas(
                'error',
                'Data RAT pada tahun tersebut sudah tersedia.'
            );

        $this->assertDatabaseCount('rat', 1);
    }

    public function test_admin_dapat_membuka_halaman_edit_rat()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $rat = Rat::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('rat.edit', $rat->id));

        $response
            ->assertStatus(200)
            ->assertViewIs('rat::edit');
    }

    public function test_admin_dapat_mengubah_rat()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $rat = Rat::factory()->create([
            'tahun' => 2026,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('rat.update', $rat->id), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-25',
                'status' => Rat::STATUS_SELESAI,
            ]);

        $response
            ->assertRedirect(route('rat.index'));

        $this->assertDatabaseHas('rat', [
            'id' => $rat->id,
            'status' => Rat::STATUS_SELESAI,
        ]);
    }

    public function test_gagal_mengubah_rat_jika_tahun_sudah_digunakan()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        Rat::factory()->create([
            'tahun' => 2026,
        ]);

        $rat = Rat::factory()->create([
            'tahun' => 2025,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('rat.update', $rat->id), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-20',
                'status' => Rat::STATUS_BELUM,
            ]);

        $response
            ->assertSessionHas(
                'error',
                'Data RAT pada tahun tersebut sudah tersedia.'
            );
    }

    public function test_anggota_tidak_dapat_melihat_hal_rat()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $response = $this
            ->actingAs($anggota)
            ->get(route('rat.index'));

        $response->assertStatus(302);
    }

    public function test_anggota_tidak_dapat_menambah_rat()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $response = $this
            ->actingAs($anggota)
            ->post(route('rat.store'), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-20',
                'status' => Rat::STATUS_BELUM,
            ]);

        $response->assertStatus(302);
    }

    public function test_anggota_tidak_dapat_mengubah_rat()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $rat = Rat::factory()->create();

        $response = $this
            ->actingAs($anggota)
            ->put(route('rat.update', $rat->id), [
                'tahun' => 2026,
                'tanggal_rat' => '2026-12-20',
                'status' => Rat::STATUS_SELESAI,
            ]);

        $response->assertStatus(302);
    }
}
