<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Entities\ShuAnggota;
use Spatie\Permission\Models\Role; 
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    /** @test */
    public function test_admin_dapat_generate_data_pencairan_shu()
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
                'tahun' => 2026
            ]
        );

        $response
            ->assertRedirect(route('pencairan.index'));

        $this->assertDatabaseHas(
            'pencairan_shu',
            [
                'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
                'nominal_pencairan' => 500000
            ]
        );
    }

    public function test_generate_gagal_jika_data_shu_belum_tersedia()
    {
        $this->actingAs($this->admin);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            'Data SHU anggota belum tersedia.'
        );

        app(\Modules\SHU\Services\PencairanShuService::class)
            ->store(2026);
    }
   
    public function test_admin_dapat_mencairkan_shu()
    {
        Storage::fake('public');

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
            route('pencairan.cairkan', $pencairan->id),
            [
                'bukti' => UploadedFile::fake()->image('bukti.jpg'),
            ]
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


        public function test_bukti_transfer_berhasil_disimpan()
        {
            Storage::fake('public');

            $this->actingAs($this->admin);

            $anggota = User::factory()->create();

            $shu = ShuAnggota::factory()->create([
                'id_anggota' => $anggota->id,
            ]);

            $pencairan = PencairanShu::factory()->create([
                'id_shu_anggota' => $shu->id,
                'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
            ]);

            $this->put(
                route('pencairan.cairkan', $pencairan->id),
                [
                    'bukti' => UploadedFile::fake()->image('transfer.jpg'),
                ]
            );

            $this->assertNotNull(
                $pencairan->fresh()->bukti
            );

            Storage::disk('public')->assertExists(
                $pencairan->fresh()->bukti
            );
        }

        public function test_tidak_dapat_mencairkan_shu_yang_sudah_dicairkan()
        {
            Storage::fake('public');

            $this->actingAs($this->admin);

            $anggota = User::factory()->create();

            $shu = ShuAnggota::factory()->create([
                'id_anggota' => $anggota->id,
            ]);

            $pencairan = PencairanShu::factory()->create([
                'id_shu_anggota' => $shu->id,
                'status' => PencairanShu::STATUS_DICAIRKAN,
            ]);

            $response = $this->put(
                route('pencairan.cairkan', $pencairan->id),
                [
                    'bukti' => UploadedFile::fake()->image('bukti.jpg'),
                ]
            );

            $response
                ->assertSessionHas(
                    'error',
                    'Pencairan SHU sudah diproses.'
                );
        }

        public function test_status_berubah_menjadi_dicairkan()
        {
            Storage::fake('public');

            $this->actingAs($this->admin);

            $anggota = User::factory()->create();

            $shu = ShuAnggota::factory()->create([
                'id_anggota' => $anggota->id,
            ]);

            $pencairan = PencairanShu::factory()->create([
                'id_shu_anggota' => $shu->id,
                'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
            ]);

            $this->put(
                route('pencairan.cairkan', $pencairan->id),
                [
                    'bukti' => UploadedFile::fake()->image('bukti.jpg'),
                ]
            );

            $this->assertEquals(
                PencairanShu::STATUS_DICAIRKAN,
                $pencairan->fresh()->status
            );
        }


        public function test_tanggal_pencairan_terisi()
        {
            Storage::fake('public');

            $this->actingAs($this->admin);

            $anggota = User::factory()->create();

            $shu = ShuAnggota::factory()->create([
                'id_anggota' => $anggota->id,
            ]);

            $pencairan = PencairanShu::factory()->create([
                'id_shu_anggota' => $shu->id,
                'status' => PencairanShu::STATUS_SIAP_DICAIRKAN,
            ]);

            $this->put(
                route('pencairan.cairkan', $pencairan->id),
                [
                    'bukti' => UploadedFile::fake()->image('bukti.jpg'),
                ]
            );

            $this->assertNotNull(
                $pencairan->fresh()->tanggal_pencairan
            );
        }

   public function test_anggota_tidak_dapat_membuat_data_pencairan_shu()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $response = $this->actingAs($anggota)
            ->post(route('pencairan.store'), [
                'tahun' => 2026,
            ]);

        $response->assertStatus(302);

        $response->assertRedirect();
    }

   public function test_anggota_tidak_dapat_mencairkan_shu()
    {
        Storage::fake('public');

        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $shu = ShuAnggota::factory()->create([
            'id_anggota' => $anggota->id,
        ]);

        $pencairan = PencairanShu::factory()->create([
            'id_shu_anggota' => $shu->id,
        ]);

        $response = $this->actingAs($anggota)
            ->put(route('pencairan.cairkan', $pencairan->id), [
                'bukti' => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertStatus(302);

        $response->assertRedirect();
    }
}
