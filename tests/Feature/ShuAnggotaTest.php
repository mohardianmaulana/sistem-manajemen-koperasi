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
        /** @test */
        public function test_sistem_dapat_menyimpan_shu_anggota()
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
             * SHU Koperasi
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
                'id_anggota'        => $user->id,
                'id_skema_pinjaman' => $skema->id,
            ]);

            /**
             * Pinjaman
             */
            Pinjaman::factory()->create([
                'id_pengajuan'      => $pengajuan->id,
                'jumlah_disetujui'  => 5000000,
                'jumlah_bunga'      => 500000,
                'total_pinjaman'    => 5500000,
                'tanggal_disetujui' => '2026-01-15',
                'status_pinjaman'   => 'selesai',
            ]);

            /**
             * Hitung SHU
             */
            $response = $this->post(route('shu.store'), [
                'tahun' => 2026,
            ]);

            /**
             * Harus redirect
             */
            $response->assertRedirect();

            /**
             * SHU Anggota berhasil tersimpan
             */
            $this->assertDatabaseHas('shu_anggota', [
                'id_anggota' => $user->id,
                'tahun'      => 2026,
            ]);
        }
    }