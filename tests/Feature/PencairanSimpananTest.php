<?php

namespace Modules\Simpanan\Tests\Feature;

use App\Models\Core\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Rat\Entities\Rat;
use Modules\Simpanan\Entities\PencairanSimpanan;
use Modules\Simpanan\Entities\SimpananSukarela;
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

    public function test_anggota_dapat_mengajukan_pencairan_simpanan()
    {
        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        Rat::factory()->create([
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_SELESAI,
        ]);

        SimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect(
            route('pencairan-simpanan.index')
        );

        $response->assertSessionHas(
            'success',
            'Pengajuan pencairan berhasil dibuat.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id_anggota' => $anggota->id,
                'nominal_pencairan' => 1000000,
                'status' => PencairanSimpanan::STATUS_PENDING,
            ]
        );
    }

    public function test_pengajuan_gagal_jika_rat_belum_tersedia()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        SimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Pengajuan pencairan hanya dapat dilakukan setelah RAT selesai.'
        );

        $this->assertDatabaseCount(
            'pencairan_simpanan',
            0
        );
    }

    public function test_pengajuan_gagal_jika_rat_belum_selesai()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        Rat::factory()->create([
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_BELUM,
        ]);

        SimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Pengajuan pencairan hanya dapat dilakukan setelah RAT selesai.'
        );

        $this->assertDatabaseCount(
            'pencairan_simpanan',
            0
        );
    }

    public function test_pengajuan_gagal_jika_masih_ada_pengajuan_pending()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        Rat::factory()->create([
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_SELESAI,
        ]);

        SimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
        ]);

        PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 500000,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Masih terdapat pengajuan pencairan yang sedang diproses.'
        );

        $this->assertDatabaseCount(
            'pencairan_simpanan',
            1
        );
    }

    public function test_pengajuan_gagal_jika_masih_ada_pengajuan_diverifikasi()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        Rat::factory()->create([
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_SELESAI,
        ]);

        SimpananSukarela::factory()->create([
            'id_anggota' => $anggota->id,
            'nilai' => 1000000,
        ]);

        PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 500000,
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Masih terdapat pengajuan pencairan yang sedang diproses.'
        );

        $this->assertDatabaseCount(
            'pencairan_simpanan',
            1
        );
    }

    public function test_pengajuan_gagal_jika_saldo_simpanan_tidak_tersedia()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        Rat::factory()->create([
            'tahun' => now()->year,
            'tanggal_rat' => now()->toDateString(),
            'status' => Rat::STATUS_SELESAI,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->post(
                route('pencairan-simpanan.store')
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Saldo simpanan sukarela tidak tersedia.'
        );

        $this->assertDatabaseCount(
            'pencairan_simpanan',
            0
        );
    }

    public function test_admin_dapat_memverifikasi_pengajuan_pencairan()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $anggota = User::factory()->create();

        PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 1000000,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $pencairan = PencairanSimpanan::latest()->first();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.verifikasi',
                    $pencairan->id
                )
            );

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

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_verifikasi
        );
    }


    public function test_admin_tidak_dapat_memverifikasi_pengajuan_yang_bukan_pending()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 1000000,
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.verifikasi',
                    $pencairan->id
                )
            );

        $response->assertSessionHas(
            'error',
            'Pengajuan pencairan hanya dapat diverifikasi jika masih berstatus pending.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
                'id_verifikator' => null,
            ]
        );
    }

    public function test_admin_dapat_menolak_pengajuan_pencairan()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 1000000,
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
                    'catatan' => 'Dokumen belum lengkap.'
                ]
            );

        $response->assertSessionHas(
            'success',
            'Pengajuan berhasil ditolak.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DITOLAK,
                'id_verifikator' => $admin->id,
                'catatan' => 'Dokumen belum lengkap.',
            ]
        );

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_verifikasi
        );
    }

    public function test_admin_tidak_dapat_menolak_pengajuan_yang_bukan_pending()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.tolak',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Dokumen belum lengkap.'
                ]
            );

        $response->assertSessionHas(
            'error',
            'Pengajuan pencairan hanya dapat ditolak jika masih berstatus pending.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
            ]
        );
    }

    public function test_bendahara_dapat_mencairkan_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'nominal_pencairan' => 1000000,
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                )
            );

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

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_pencairan
        );
    }

    public function test_bendahara_tidak_dapat_mencairkan_pengajuan_yang_bukan_diverifikasi()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                )
            );

        $response->assertSessionHas(
            'error',
            'Pencairan hanya dapat dilakukan pada pengajuan yang telah diverifikasi.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_PENDING,
            ]
        );
    }

    public function test_status_berubah_menjadi_dicairkan()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $this->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                )
            );

        $this->assertEquals(
            PencairanSimpanan::STATUS_DICAIRKAN,
            $pencairan->fresh()->status
        );
    }

    public function test_tanggal_pencairan_dan_bendahara_tercatat()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $this->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                )
            );

        $this->assertNotNull(
            $pencairan->fresh()->tanggal_pencairan
        );

        $this->assertEquals(
            $bendahara->id,
            $pencairan->fresh()->id_bendahara
        );
    }

    public function test_bendahara_dapat_memberikan_status_gagal()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Transfer gagal karena rekening tidak aktif.'
                ]
            );

        $response->assertSessionHas(
            'success',
            'Status pencairan berhasil diperbarui.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_GAGAL,
                'id_bendahara' => $bendahara->id,
                'catatan' => 'Transfer gagal karena rekening tidak aktif.',
            ]
        );
    }

    public function test_bendahara_tidak_dapat_memberikan_status_gagal_jika_bukan_diverifikasi()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $anggota = User::factory()->create();

        $pencairan = PencairanSimpanan::factory()->create([
            'id_anggota' => $anggota->id,
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Transfer gagal.'
                ]
            );

        $response->assertSessionHas(
            'error',
            'Status gagal hanya dapat diberikan pada pengajuan yang telah diverifikasi.'
        );

        $this->assertDatabaseHas(
            'pencairan_simpanan',
            [
                'id' => $pencairan->id,
                'status' => PencairanSimpanan::STATUS_PENDING,
            ]
        );
    }

    public function test_catatan_gagal_berhasil_disimpan()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $this->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Rekening tujuan tidak ditemukan.'
                ]
            );

        $this->assertEquals(
            'Rekening tujuan tidak ditemukan.',
            $pencairan->fresh()->catatan
        );
    }

    public function test_id_bendahara_tersimpan_saat_status_gagal()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $this->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Transfer gagal.'
                ]
            );

        $this->assertEquals(
            $bendahara->id,
            $pencairan->fresh()->id_bendahara
        );
    }

    public function test_anggota_tidak_dapat_memverifikasi_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

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

    public function test_anggota_tidak_dapat_menolak_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'anggota'
        ]);

        $anggota = User::factory()->create();

        $anggota->assignRole('anggota');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($anggota)
            ->put(
                route(
                    'pencairan-simpanan.tolak',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Tidak sesuai.'
                ]
            );

        $response->assertStatus(302);
    }

    public function test_admin_tidak_dapat_mencairkan_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.cairkan',
                    $pencairan->id
                )
            );

        $response->assertStatus(302);
    }

    public function test_admin_tidak_dapat_memberikan_status_gagal()
    {
        Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $admin = User::factory()->create();

        $admin->assignRole('admin');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_DIVERIFIKASI,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'pencairan-simpanan.gagal',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Transfer gagal.'
                ]
            );

        $response->assertStatus(302);
    }

    public function test_bendahara_tidak_dapat_memverifikasi_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

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

    public function test_bendahara_tidak_dapat_menolak_pengajuan()
    {
        Role::firstOrCreate([
            'name' => 'bendahara'
        ]);

        $bendahara = User::factory()->create();

        $bendahara->assignRole('bendahara');

        $pencairan = PencairanSimpanan::factory()->create([
            'status' => PencairanSimpanan::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($bendahara)
            ->put(
                route(
                    'pencairan-simpanan.tolak',
                    $pencairan->id
                ),
                [
                    'catatan' => 'Tidak sesuai.'
                ]
            );

        $response->assertStatus(302);
    }
}