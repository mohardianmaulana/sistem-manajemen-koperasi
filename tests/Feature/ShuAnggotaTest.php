<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use App\Models\Core\User;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Modules\SHU\Entities\ShuKoperasi;
use Modules\Simpanan\Entities\SimpananPokok;
use Modules\Simpanan\Entities\SimpananSukarela;
use Modules\Simpanan\Entities\SimpananWajib;
use Tests\TestCase;

class ShuAnggotaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
  public function test_sistem_dapat_menyimpan_shu_anggota()
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
         * SHU koperasi
         */
        ShuKoperasi::factory()->create([
            'tahun'           => 2026,
            'jasa_simpanan'   => 1000000,
            'jasa_pinjaman'   => 500000,
            'dana_cadangan'   => 200000,
            'jasa_pengurus'   => 150000,
            'dana_sosial'     => 100000,
            'total_shu'       => 1950000,
        ]);

        /**
         * Simpanan Pokok
         */
        SimpananPokok::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'status'     => 'selesai',
            'tanggal'    => '2026-01-10',
        ]);

        /**
         * Simpanan Wajib
         */
        SimpananWajib::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
            'periode'    => '2026-01-01',
        ]);

        /**
         * Simpanan Sukarela
         */
        SimpananSukarela::factory()->create([
            'id_anggota' => $user->id,
            'nilai'      => 100000,
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
            'id_anggota'         => $user->id,
            'id_skema_pinjaman'  => $skema->id,
        ]);

        /**
         * Pinjaman
         */
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        /**
         * Angsuran
         */
        Angsuran::factory()->create([
            'id_pinjaman'          => $pinjaman->id,
            'jumlah_angsuran'      => 50000,
            'tanggal_jatuh_tempo'  => '2026-02-01',
            'status_bayar'         => 'lunas',
        ]);

        /**
         * Hitung SHU
         */
        $response = $this->post(route('shu.store'), [
            'tahun' => 2026,
        ]);

        /**
         * Harus redirect setelah berhasil
         */
        $response->assertRedirect();

        /**
         * Data SHU tersimpan
         */
        $this->assertDatabaseHas('shu_anggota', [
            'id_anggota' => $user->id,
            'tahun'      => 2026,
        ]);
    }
}
