<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;
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
    public function test_admin_dapat_membuat_shu_koperasi()
    {
        /**
         * Login Admin
         */
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Simpanan Pokok
         */
        SimpananPokok::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'tanggal'    => '2026-01-10',
            'status'     => 'selesai',
        ]);

        /**
         * Simpanan Wajib
         */
        SimpananWajib::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 50000,
            'periode'    => '2026-01-01',
        ]);

        /**
         * Simpanan Sukarela
         */
        SimpananSukarela::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 25000,
            'periode'    => '2026-01-01',
        ]);

        /**
         * Skema Pinjaman
         */
        $skema = SkemaPinjaman::factory()->create();

        /**
         * Pengajuan Pinjaman
         */
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota'        => $user->id,
            'id_skema_pinjaman' => $skema->id,
        ]);

        /**
         * Pinjaman
         */
        Pinjaman::factory()->create([
            'id_pengajuan'      => $pengajuan->id,
            'jumlah_disetujui'  => 5000000,
            'jumlah_bunga'      => 75000,
            'total_pinjaman'    => 5075000,
            'tanggal_disetujui' => '2026-01-15',
            'status_pinjaman'   => 'aktif',
        ]);

        /**
         * Store SHU
         */
        $response = $this->post(route('shu-koperasi.store'), [

            'tahun'           => 2026,
            'dana_cadangan'   => 100000,
            'jasa_pengurus'   => 50000,
            'dana_sosial'     => 25000,

        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'tahun' => 2026,

            'dana_cadangan' => 100000,

            'jasa_pengurus' => 50000,

            'dana_sosial' => 25000,

        ]);
    }

    /** @test */
    public function test_admin_dapat_update_shu_koperasi()
    {
        /**
         * Login Admin
         */
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        $shu = ShuKoperasi::factory()->create([

            'tahun' => 2026,

            'jasa_simpanan' => 200000,

            'jasa_pinjaman' => 100000,

            'dana_cadangan' => 100000,

            'jasa_pengurus' => 50000,

            'dana_sosial' => 25000,

            'total_shu' => 475000,

        ]);

        $response = $this->put(
            route('shu-koperasi.update', $shu->id),
            [

                'dana_cadangan' => 200000,

                'jasa_pengurus' => 100000,

                'dana_sosial' => 50000,

            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'id' => $shu->id,

            'dana_cadangan' => 200000,

            'jasa_pengurus' => 100000,

            'dana_sosial' => 50000,

        ]);
    }

    /** @test */
    public function test_total_shu_dihitung_otomatis()
    {
        /**
         * Login Admin
         */
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->actingAs($user);

        /**
         * Simpanan
         */
        SimpananPokok::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'tanggal'    => '2026-01-10',
            'status'     => 'selesai',
        ]);

        SimpananWajib::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'periode'    => '2026-01-01',
        ]);

        SimpananSukarela::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'periode'    => '2026-01-01',
        ]);

        /**
         * Skema
         */
        $skema = SkemaPinjaman::factory()->create();

        /**
         * Pengajuan
         */
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota'        => $user->id,
            'id_skema_pinjaman' => $skema->id,
        ]);

        /**
         * Pinjaman
         */
        Pinjaman::factory()->create([
            'id_pengajuan'      => $pengajuan->id,
            'jumlah_disetujui'  => 5000000,
            'jumlah_bunga'      => 50000,
            'total_pinjaman'    => 5050000,
            'tanggal_disetujui' => '2026-01-10',
            'status_pinjaman'   => 'aktif',
        ]);

        /**
         * Store SHU
         */
        $this->post(route('shu-koperasi.store'), [

            'tahun' => 2026,

            'dana_cadangan' => 50000,

            'jasa_pengurus' => 50000,

            'dana_sosial' => 50000,

        ]);

        /**
         * Jasa Simpanan = 300000
         * Jasa Pinjaman = 50000
         * Dana Cadangan = 50000
         * Jasa Pengurus = 50000
         * Dana Sosial = 50000
         *
         * Total SHU = 500000
         */
        $this->assertDatabaseHas('sisa_hasil_usaha', [

            'tahun' => 2026,

            'jasa_simpanan' => 300000,

            'jasa_pinjaman' => 50000,

            'total_shu' => 500000,

        ]);
    }
}
