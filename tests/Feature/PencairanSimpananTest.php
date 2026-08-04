<?php

namespace Modules\Simpanan\Tests\Feature;

use App\Models\Core\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Simpanan\Entities\PencairanSimpanan;
use Modules\Simpanan\Entities\SimpananPokok;
use Spatie\Permission\Models\Role;

class PencairanSimpananTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'bendahara',
            'guard_name' => 'web',
        ]);
    }
   public function test_anggota_dapat_membuka_halaman_edit_pencairan()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->get(route(
                'pencairan-simpanan.edit',
                $pencairan->id
            ));

        $response->assertStatus(200);

        $response->assertViewIs(
            'simpanan::pencairan.edit'
        );

        $response->assertViewHas('data');
    }

   public function test_anggota_dapat_mengubah_pengajuan_pencairan()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        SimpananPokok::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai'      => 1000000,
            'status'     => 'selesai',
        ]);

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota'          => $anggota->id,
            'status'              => PencairanSimpanan::STATUS_PENDING,
            'nominal_pencairan'   => 100000,
            'alasan'              => 'Lama',
        ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route(
                    'pencairan-simpanan.update',
                    $pencairan->id
                ),
                [
                    'nominal_pencairan' => 150000,
                    'alasan'            => 'Keperluan keluarga',
                ]
            );

        $response->assertRedirect(
            route('pencairan-simpanan.index')
        );

        $response->assertSessionHas(
            'success',
            'Pengajuan pencairan berhasil diperbarui.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id'                  => $pencairan->id,
                'nominal_pencairan'   => 150000,
                'alasan'              => 'Keperluan keluarga',
            ]
        );
    }

    public function test_anggota_tidak_dapat_mengubah_pengajuan_milik_anggota_lain()
    {
        $anggota1 = User::factory()->create();
        $anggota1->assignRole('anggota');

        $anggota2 = User::factory()->create();
        $anggota2->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota2->id,
        ]);

        $response = $this
            ->actingAs($anggota1)
            ->get(route(
                'pencairan-simpanan.edit',
                $pencairan->id
            ));

        $response->assertStatus(404);
    }

    public function test_anggota_tidak_dapat_mengubah_pengajuan_yang_sudah_diverifikasi()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create([
                'id_anggota' => $anggota->id,
            ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route('pencairan-simpanan.update', $pencairan->id),
                [
                    'nominal_pencairan' => 100000,
                    'alasan' => 'Update',
                ]
            );

        $response->assertSessionHas('error');

        $this->assertEquals(
            PencairanSimpanan::STATUS_DIVERIFIKASI,
            $pencairan->fresh()->status
        );
    }

    public function test_anggota_tidak_dapat_mengubah_pengajuan_yang_sudah_ditolak()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()
            ->ditolak()
            ->create([
                'id_anggota' => $anggota->id,
            ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route('pencairan-simpanan.update', $pencairan->id),
                [
                    'nominal_pencairan' => 100000,
                    'alasan' => 'Update',
                ]
            );

        $response->assertSessionHas('error');
    }

    public function test_anggota_tidak_dapat_mengubah_pengajuan_yang_sudah_dicairkan()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()
            ->dicairkan()
            ->create([
                'id_anggota' => $anggota->id,
            ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route('pencairan-simpanan.update', $pencairan->id),
                [
                    'nominal_pencairan' => 100000,
                    'alasan' => 'Update',
                ]
            );

        $response->assertSessionHas('error');
    }

    public function test_anggota_tidak_dapat_mengubah_pengajuan_yang_sudah_gagal()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()
            ->gagal()
            ->create([
                'id_anggota' => $anggota->id,
            ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route('pencairan-simpanan.update', $pencairan->id),
                [
                    'nominal_pencairan' => 100000,
                    'alasan' => 'Update',
                ]
            );

        $response->assertSessionHas('error');
    }

    public function test_anggota_tidak_dapat_mengubah_nominal_melebihi_saldo()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        SimpananPokok::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 100000,
            'status' => 'selesai',
        ]);

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_PENDING,
            'nominal_pencairan' => 50000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route('pencairan-simpanan.update', $pencairan->id),
                [
                    'nominal_pencairan' => 500000,
                    'alasan' => 'Update',
                ]
            );

        $response->assertSessionHas('error');
    }

    public function test_admin_dapat_memverifikasi_pengajuan_pencairan()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.verifikasi',
                    $pencairan->id
                )
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Pengajuan berhasil diverifikasi.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
                'id_verifikator' => $admin->id,
            ]
        );
    }

    public function test_admin_dapat_menolak_pengajuan_pencairan()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.tolak',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Saldo belum memenuhi syarat.',
                ]
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Pengajuan berhasil ditolak.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DITOLAK,
                'catatan' => 'Saldo belum memenuhi syarat.',
                'id_verifikator' => $admin->id,
            ]
        );
    }

    public function test_admin_tidak_dapat_menolak_pengajuan_tanpa_catatan()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->from(route('pencairan-simpanan.index'))
            ->put(
                route(
                    'pencairan-simpanan.tolak',
                    $pencairan->id
                ),
                [
                    'catatan' => '',
                ]
            );

        $response
            ->assertRedirect(route('pencairan-simpanan.index'));

        $response->assertSessionHasErrors('catatan');
    }

    public function test_anggota_tidak_dapat_memverifikasi_pengajuan()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()->create();

        $response = $this
            ->actingAs($anggota)
            ->put(
                route(
                    'pencairan-simpanan.verifikasi',
                    $pencairan->id
                )
            );

        $response->assertStatus(302);
    }

    public function test_bendahara_tidak_dapat_memverifikasi_pengajuan()
    {
        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create();

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.verifikasi',
                    $pencairan->id
                )
            );

        $response->assertStatus(302);
    }

    public function test_bendahara_dapat_mencairkan_pengajuan()
    {
        Storage::fake('public');

        $bendahara = User::factory()->create();
        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                ),
                [
                    'bukti_transfer' => UploadedFile::fake()->image('bukti.png'),
                ]
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'success',
            'Pencairan berhasil dilakukan.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DICAIRKAN,
                'id_bendahara' => $bendahara->id,
            ]
        );
    }

    public function test_bendahara_wajib_mengunggah_bukti_transfer()
    {
        $bendahara = User::factory()->create();
        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->from(route('pencairan-simpanan.index'))
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                ),
                []
            );

        $response
            ->assertRedirect(route('pencairan-simpanan.index'));

        $response->assertSessionHasErrors(
            'bukti_transfer'
        );
    }

    public function test_bendahara_dapat_menandai_pencairan_gagal()
    {
        $bendahara = User::factory()->create();
        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Transfer gagal.',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_GAGAL,
                'catatan' => 'Transfer gagal.',
                'id_bendahara' => $bendahara->id,
            ]
        );
    }

    public function test_bendahara_wajib_mengisi_catatan_saat_pencairan_gagal()
    {
        $bendahara = User::factory()->create();
        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->from(route('pencairan-simpanan.index'))
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => '',
                ]
            );

        $response
            ->assertRedirect(route('pencairan-simpanan.index'));

        $response->assertSessionHasErrors('catatan');
    }

    public function test_admin_tidak_dapat_mencairkan_pengajuan()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                ),
                [
                    'bukti_transfer' => UploadedFile::fake()->image('bukti.png'),
                ]
            );

        $response->assertRedirect();
    }

    public function test_anggota_tidak_dapat_mencairkan_pengajuan()
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($anggota)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                ),
                [
                    'bukti_transfer' => UploadedFile::fake()->image('bukti.png'),
                ]
            );

        $response->assertRedirect();
    }

    public function test_dashboard_anggota_menampilkan_total_saldo()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        SimpananPokok::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
            'status' => 'selesai',
        ]);

        $response = $this
            ->actingAs($anggota)
            ->get(route('pencairan-simpanan.index'));

        $response->assertStatus(200);

        $response->assertViewHas('saldo');
    }

    public function test_dashboard_anggota_menampilkan_total_pending()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalPending');
    }

    public function test_dashboard_anggota_menampilkan_total_dicairkan()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        PencairanSimpanan::factory()
            ->dicairkan()
            ->create([
                'id_anggota' => $anggota->id,
            ]);

        $response = $this
            ->actingAs($anggota)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalDicairkan');
    }

    public function test_dashboard_admin_menampilkan_total_pending()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        PencairanSimpanan::factory()->count(3)->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalPending');
    }

    public function test_dashboard_admin_menampilkan_total_diverifikasi()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        PencairanSimpanan::factory()
            ->count(2)
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalDiverifikasi');
    }

    public function test_dashboard_admin_menampilkan_total_ditolak()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        PencairanSimpanan::factory()
            ->count(2)
            ->ditolak()
            ->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalDitolak');
    }

    public function test_dashboard_bendahara_menampilkan_total_siap_dicairkan()
    {
        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        PencairanSimpanan::factory()
            ->count(2)
            ->diverifikasi()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalSiapDicairkan');
    }

    public function test_dashboard_bendahara_menampilkan_total_sudah_dicairkan()
    {
        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        PencairanSimpanan::factory()
            ->count(2)
            ->dicairkan()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalSudahDicairkan');
    }

    public function test_dashboard_bendahara_menampilkan_total_gagal()
    {
        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        PencairanSimpanan::factory()
            ->count(2)
            ->gagal()
            ->create();

        $response = $this
            ->actingAs($bendahara)
            ->get(route('pencairan-simpanan.index'));

        $response->assertViewHas('totalGagal');
    }

    public function test_filter_pencairan_berdasarkan_status()
    {
        $admin = User::factory()->create();

        $admin->assignRole('admin');

        PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        PencairanSimpanan::factory()
            ->dicairkan()
            ->create();

        $response = $this
            ->actingAs($admin)
            ->get(route(
                'pencairan-simpanan.index',
                [
                    'status' => PencairanSimpanan::STATUS_PENDING,
                ]
            ));

        $response->assertStatus(200);

        $response->assertViewHas('data');
    }
}