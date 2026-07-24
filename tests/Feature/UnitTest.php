<?php

namespace Tests\Feature;

use App\Models\Core\Unit;
use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);

        $this->admin = User::factory()->create();

        $this->admin->assignRole('admin');
    }

    /** @test */
    public function test_admin_dapat_melihat_halaman_unit()
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('unit.index'));

        $response->assertStatus(200);
        $response->assertViewIs('unit::index');
    }

    /** @test */
    public function test_admin_dapat_menambah_unit()
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('unit.store'), [
                'nama' => 'Unit Akademik',
            ]);

        $response->assertRedirect(route('unit.index'));

        $this->assertDatabaseHas('units', [
            'nama' => 'Unit Akademik',
        ]);
    }


    /** @test */
    public function test_admin_dapat_mengubah_unit()
    {
        $unit = Unit::create([
            'nama' => 'Unit Lama',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->put(route('unit.update', $unit->id), [
                'nama' => 'Unit Baru',
            ]);

        $response->assertRedirect(route('unit.index'));

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'nama' => 'Unit Baru',
        ]);
    }

    /** @test */
    public function test_admin_dapat_menghapus_unit()
    {
        $unit = Unit::create([
            'nama' => 'Unit Hapus',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('unit.destroy', $unit->id));

        $response->assertRedirect(route('unit.index'));

        $this->assertDatabaseMissing('units', [
            'id' => $unit->id,
        ]);
    }

    /** @test */
    public function test_admin_dapat_mencari_unit()
    {
        Unit::create([
            'nama' => 'Unit Akademik',
        ]);

        Unit::create([
            'nama' => 'Unit Keuangan',
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('unit.index', [
                'search' => 'Akademik',
            ]));

        $response->assertStatus(200);

        $response->assertSee('Unit Akademik');

        $response->assertDontSee('Unit Keuangan');
    }
}