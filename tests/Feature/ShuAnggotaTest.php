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
        $adminRole = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        $anggotaRole = Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin
         */
        $user = User::factory()->create();

        /**
         * User merupakan admin sekaligus anggota koperasi
         */
        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Data SHU Koperasi
         */
        ShuKoperasi::factory()->create([
            'periode_awal'  => '2026-01-01',
            'periode_akhir' => '2026-12-31',
            'jasa_simpanan' => 1000000,
            'jasa_pinjaman' => 500000,
            'jasa_pengurus' => 150000,
            'dana_cadangan' => 200000,
            'dana_sosial'   => 100000,
            'total_shu'     => 1950000,
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
            'lama_angsuran'     => 10,
        ]);

        /**
         * Pinjaman
         */
        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan'      => $pengajuan->id,
            'jumlah_disetujui'  => 5000000,
            'jumlah_bunga'      => 500000,
            'total_pinjaman'    => 5500000,
            'tanggal_disetujui' => '2026-01-15',
            'status_pinjaman'   => 'aktif',
        ]);

        /**
         * Lima angsuran telah dibayar
         */
        Angsuran::factory()->count(5)->create([
            'id_pinjaman'         => $pinjaman->id,
            'status_bayar'        => 'lunas',
            'tanggal_jatuh_tempo' => '2026-02-01',
        ]);

        /**
         * Menghitung SHU anggota
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal'          => '2026-01-01',

            'periode_akhir'         => '2026-12-31',

            'persen_jasa_pengurus'  => 20,

            'persen_pajak'          => 10,

        ]);

        /**
         * Memastikan proses berhasil
         */
        $response->assertRedirect(route('shu.index'));

        /**
         * Memastikan data SHU anggota berhasil disimpan
         */
        $this->assertDatabaseHas('shu_anggota', [

            'id_anggota'   => $user->id,

            'periode_awal' => '2026-01-01',

            'periode_akhir'=> '2026-12-31',

        ]);
    }

    public function test_gagal_menghitung_shu_jika_shu_koperasi_tidak_ditemukan()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $admin = User::factory()->create();

        $admin->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($admin);

        /**
         * Melakukan perhitungan SHU tanpa data SHU koperasi
         */
        $response = $this->from(route('shu.index'))
            ->post(route('shu.store'), [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'persen_jasa_pengurus' => 20,

                'persen_pajak' => 10,

            ]);

        /**
         * Harus kembali ke halaman sebelumnya
         */
        $response->assertRedirect(route('shu.index'));

        /**
         * Memastikan pesan error muncul
         */
        $response->assertSessionHasErrors([
            'error' => 'Data SHU koperasi pada periode tersebut belum tersedia.'
        ]);

        /**
         * Memastikan tidak ada data SHU anggota yang tersimpan
         */
        $this->assertDatabaseCount('shu_anggota', 0);
    }

    public function test_gagal_menghitung_shu_jika_belum_ada_transaksi()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $admin = User::factory()->create();

        $admin->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($admin);

        /**
         * Data SHU Koperasi
         */
        ShuKoperasi::factory()->create([
            'periode_awal'  => '2026-01-01',
            'periode_akhir' => '2026-12-31',
            'jasa_simpanan' => 1000000,
            'jasa_pinjaman' => 500000,
            'jasa_pengurus' => 150000,
            'dana_cadangan' => 200000,
            'dana_sosial'   => 100000,
            'total_shu'     => 1950000,
        ]);

        /**
         * Tidak membuat transaksi simpanan maupun pinjaman
         */

        /**
         * Menjalankan proses perhitungan SHU
         */
        $response = $this->from(route('shu.index'))
            ->post(route('shu.store'), [

                'periode_awal' => '2026-01-01',

                'periode_akhir' => '2026-12-31',

                'persen_jasa_pengurus' => 20,

                'persen_pajak' => 10,

            ]);

        /**
         * Harus kembali ke halaman sebelumnya
         */
        $response->assertRedirect(route('shu.index'));

        /**
         * Memastikan pesan error muncul
         */
        $response->assertSessionHasErrors([
            'error' => 'Perhitungan SHU tidak dapat dilakukan karena belum terdapat transaksi.'
        ]);

        /**
         * Memastikan data SHU anggota tidak tersimpan
         */
        $this->assertDatabaseCount('shu_anggota', 0);
    }

    public function test_gagal_menghitung_shu_jika_periode_awal_kosong()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name'       => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $user = User::factory()->create();

        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa periode awal
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal' => '',

            'periode_akhir' => '2026-12-31',

            'persen_jasa_pengurus' => 20,

            'persen_pajak' => 10,

        ]);

        /**
         * Memastikan validasi gagal
         */
        $response->assertSessionHasErrors('periode_awal');
    }

    public function test_gagal_menghitung_shu_jika_periode_akhir_lebih_kecil()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $user = User::factory()->create();

        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Mengirim periode akhir lebih kecil dari periode awal
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal' => '2026-12-31',

            'periode_akhir' => '2026-01-01',

            'persen_jasa_pengurus' => 20,

            'persen_pajak' => 10,

        ]);

        /**
         * Memastikan validasi gagal
         */
        $response->assertSessionHasErrors('periode_akhir');
    }

    public function test_gagal_menghitung_shu_jika_periode_akhir_kosong()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $user = User::factory()->create();

        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa periode akhir
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '',

            'persen_jasa_pengurus' => 20,

            'persen_pajak' => 10,

        ]);

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('periode_akhir');
    }

    public function test_gagal_menghitung_shu_jika_persen_pajak_kosong()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $user = User::factory()->create();

        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa persen pajak
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'persen_jasa_pengurus' => 20,

            'persen_pajak' => '',

        ]);

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('persen_pajak');
    }

    public function test_gagal_menghitung_shu_jika_persen_jasa_pengurus_kosong()
    {
        /**
         * Membuat role admin
         */
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        /**
         * Membuat role anggota
         */
        Role::firstOrCreate([
            'name' => 'anggota',
            'guard_name' => 'web',
        ]);

        /**
         * Login sebagai admin sekaligus anggota
         */
        $user = User::factory()->create();

        $user->assignRole([
            'admin',
            'anggota',
        ]);

        $this->actingAs($user);

        /**
         * Mengirim request tanpa persen jasa pengurus
         */
        $response = $this->post(route('shu.store'), [

            'periode_awal' => '2026-01-01',

            'periode_akhir' => '2026-12-31',

            'persen_jasa_pengurus' => '',

            'persen_pajak' => 10,

        ]);

        /**
         * Validasi gagal
         */
        $response->assertSessionHasErrors('persen_jasa_pengurus');
    }
}