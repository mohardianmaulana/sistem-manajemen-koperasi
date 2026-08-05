<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Entities\ShuAnggota;
use Spatie\Permission\Models\Role; 
use Modules\Rat\Entities\Rat;
use Tests\TestCase;

class PencairanShuTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);

        $this->admin = User::factory()->create();

        $this->admin->assignRole('admin');
    }

    public function test_generate_pencairan_gagal_jika_data_rat_belum_tersedia()
    {
        $this->actingAs($this->admin);

        ShuAnggota::factory()->create([
            'shu_anggota' => 500000,
            'periode_awal' => '2026-01-01',
            'periode_akhir' => '2026-12-31',
        ]);

        $response = $this->post(
            route('pencairan.store'),
            [
                'tahun' => 2026,
            ]
        );

        $response
            ->assertRedirect();

        $response
            ->assertSessionHas(
                'error',
                'Generate pencairan SHU hanya dapat dilakukan setelah RAT selesai.'
            );

        $this->assertDatabaseMissing(
            'pencairan_shu',
            [
                'nominal_pencairan' => 500000,
            ]
        );
    }


    public function test_generate_pencairan_gagal_jika_rat_belum_selesai()
    {
        $this->actingAs($this->admin);

        Rat::factory()->create([
            'tahun' => 2026,
            'tanggal_rat' => '2026-02-15',
            'status' => Rat::STATUS_BELUM,
        ]);

        ShuAnggota::factory()->create([
            'shu_anggota' => 500000,
            'periode_awal' => '2026-01-01',
            'periode_akhir' => '2026-12-31',
        ]);

        $response = $this->post(
            route('pencairan.store'),
            [
                'tahun' => 2026,
            ]
        );

        $response
            ->assertRedirect();

        $response
            ->assertSessionHas(
                'error',
                'Generate pencairan SHU hanya dapat dilakukan setelah RAT selesai.'
            );

        $this->assertDatabaseMissing(
            'pencairan_shu',
            [
                'nominal_pencairan' => 500000,
            ]
        );
    }

    public function test_generate_gagal_jika_data_shu_belum_tersedia()
    {
        $this->actingAs($this->admin);

        Rat::factory()->create([
            'tahun' => 2026,
            'tanggal_rat' => '2026-02-15',
            'status' => Rat::STATUS_SELESAI,
        ]);

        $response = $this->post(
            route('pencairan.store'),
            [
                'tahun' => 2026,
            ]
        );

        $response
            ->assertRedirect();

        $response
            ->assertSessionHas(
                'error',
                'Data SHU anggota belum tersedia.'
            );

        $this->assertDatabaseCount(
            'pencairan_shu',
            0
        );
    }

    public function test_admin_dapat_generate_data_pencairan_shu()
    {
        $this->actingAs($this->admin);

        Rat::factory()->create([
            'tahun' => 2026,
            'tanggal_rat' => '2026-02-15',
            'status' => Rat::STATUS_SELESAI,
        ]);

        ShuAnggota::factory()->create([
            'shu_anggota' => 500000,
            'periode_awal' => '2026-01-01',
            'periode_akhir' => '2026-12-31',
        ]);

        $response = $this->post(
            route('pencairan.store'),
            [
                'tahun' => 2026,
            ]
        );

        $response->assertRedirect(
            route('pencairan.index')
        );

        $this->assertDatabaseHas(
            'pencairan_shu',
            [
                'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
                'nominal_pencairan' => 500000,
            ]
        );
    }

    public function test_admin_dapat_mencairkan_shu()
    {
        $this->actingAs($this->admin);

        $anggota = User::factory()->create();

        $shu = ShuAnggota::factory()->create([
            'id_anggota' => $anggota->id,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
        ]);

        $response = $this->put(
            route('pencairan.cairkan', $pencairan->id)
        );

        $response->assertRedirect(
            route('pencairan.index')
        );

        $this->assertDatabaseHas(
            'pencairan_shu',
            [
                'id' => $pencairan->id,
                'status' => PencairanShu::STATUS_DICAIRKAN,
                'dicairkan_oleh' => $this->admin->id,
            ]
        );
    }

    public function test_tidak_dapat_mencairkan_shu_yang_sudah_dicairkan()
    {
        $this->actingAs($this->admin);

        $shu = ShuAnggota::factory()->create();

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_DICAIRKAN,
        ]);

        $response = $this->put(
            route('pencairan.cairkan', $pencairan->id)
        );

        $response->assertSessionHas(
            'error',
            'Pencairan SHU sudah diproses.'
        );
    }

    public function test_status_berubah_menjadi_dicairkan()
    {
        $this->actingAs($this->admin);

        $shu = ShuAnggota::factory()->create();

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
        ]);

        $this->put(
            route('pencairan.cairkan', $pencairan->id)
        );

        $this->assertEquals(
            PencairanShu::STATUS_DICAIRKAN,
            $pencairan->fresh()->status
        );
    }

    public function test_tanggal_pencairan_terisi()
    {
        $this->actingAs($this->admin);

        $shu = ShuAnggota::factory()->create();

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
            'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
        ]);

        $this->put(
            route('pencairan.cairkan', $pencairan->id)
        );

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_pencairan
        );
    }
}
