<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Pinjaman\Entities\Jaminan;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Tests\TestCase;

class PengajuanPinjamanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_create_pengajuan_pinjaman_sukses()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_create_pengajuan_pinjaman_gagal_masih_ada_pinjaman_yang_berjalan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
        ]);

        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseCount('pengajuan_pinjaman', 1);
    }

    public function test_create_pengajuan_pinjaman_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_dari_0()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => -1,
            'lama_angsuran' => -1,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_atau_lebih_besar_dari_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 499999,
            'lama_angsuran' => 25,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_gagal_no_hp_no_ktp_dan_no_rekening_tidak_sesuai_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1111',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_dengan_jaminan_sukses()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);

        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $this->assertDatabaseCount('pengajuan_jaminan', 1);
    }

    public function test_create_pengajuan_pinjaman_dengan_jaminan_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_dari_0()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);

        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => -1,
            'lama_angsuran' => -1,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_dengan_jaminan_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_atau_lebih_besar_dari_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);

        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 499999,
            'lama_angsuran' => 25,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_dengan_jaminan_gagal_no_hp_no_ktp_dan_no_rekening_tidak_sesuai_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1111',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_dengan_jaminan_gagal_file_jaminan_tidak_sesuai_dengan_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => 'xxxx',
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jaminan.*.file',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_update_pengajuan_pinjaman_sukses()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_dari_0()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => -1,
            'lama_angsuran' => -1,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_atau_lebih_besar_dari_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 499999,
            'lama_angsuran' => 25,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_gagal_no_hp_no_ktp_dan_no_rekening_tidak_sesuai_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'tidak',
        ]);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 2000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1234',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_dengan_jaminan_sukses()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);

        $this->assertDatabaseCount('pengajuan_jaminan', 1);
    }

    public function test_update_pengajuan_pinjaman_dengan_jaminan_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_dari_0()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => -1,
            'lama_angsuran' => -1,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_dengan_jaminan_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_atau_lebih_besar_dari_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 499999,
            'lama_angsuran' => 25,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_dengan_jaminan_gagal_no_hp_no_ktp_dan_no_rekening_tidak_sesuai_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 1000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1234',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => UploadedFile::fake()
                        ->create('jaminan.pdf', 100, 'application/pdf'),
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_pengajuan_pinjaman_dengan_jaminan_gagal_file_jaminan_tidak_sesuai_dengan_ketentuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 2000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'jaminan' => [
                [
                    'id_jaminan' => $jaminan->id,
                    'file' => 'xxxx',
                ]
            ],
        ]);

        $response->assertSessionHasErrors([
            'jaminan.*.file'
        ]);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-06-01',
            'jumlah_pengajuan' => 500000,
            'lama_angsuran' => 18,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_update_status_verifikasi_pengajuan_pinjaman_dengan_jaminan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);
    }

    public function test_update_status_verifikasi_file_jaminan()
    {
        $user = User::factory()->create();
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pengajuan->jaminan()->attach($jaminan->id, [
            'file_jaminan' => 'jaminan/ktp.pdf',
            'status_verifikasi' => 'menunggu',
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/verifikasi/{$pengajuan->id}", [
            'id_jaminan' => $jaminan->id,
        ]);

        $this->assertDatabaseHas('pengajuan_jaminan', [
            'id_pengajuan' => $pengajuan->id,
            'id_jaminan' => $jaminan->id,
            'status_verifikasi' => 'verifikasi',
        ]);
    }

    public function test_update_status_tolak_verifikasi_file_jaminan()
    {
        $user = User::factory()->create();
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pengajuan->jaminan()->attach($jaminan->id, [
            'file_jaminan' => 'jaminan/ktp.pdf',
            'status_verifikasi' => 'menunggu',
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/tolak/{$pengajuan->id}", [
            'id_jaminan' => $jaminan->id,
            'keterangan' => 'pengen aja',
        ]);

        $this->assertDatabaseHas('pengajuan_jaminan', [
            'id_pengajuan' => $pengajuan->id,
            'id_jaminan' => $jaminan->id,
            'status_verifikasi' => 'ditolak',
            'keterangan' => 'pengen aja',
        ]);
    }

    public function test_update_status_persetujuan_awal_pengajuan_pinjaman()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/teruskan/{$pengajuan->id}",
        [
            'status_pengajuan' => 'persetujuan_awal',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'persetujuan_awal',
        ]);

        $this->assertDatabaseCount('persetujuan', 1);

        $this->assertDatabaseHas('persetujuan', 
        [
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);
    }

    public function test_update_status_persetujuan_awal_pengajuan_pinjaman_dengan_jaminan()
    {
        $user = User::factory()->create();
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pengajuan->jaminan()->attach($jaminan->id, [
            'file_jaminan' => 'jaminan/ktp.pdf',
            'status_verifikasi' => 'menunggu',
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/verifikasi/{$pengajuan->id}", [
            'id_jaminan' => $jaminan->id,
        ]);

        $this->assertDatabaseHas('pengajuan_jaminan', [
            'id_pengajuan' => $pengajuan->id,
            'id_jaminan' => $jaminan->id,
            'status_verifikasi' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/teruskan/{$pengajuan->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'persetujuan_awal',
        ]);

        $this->assertDatabaseCount('persetujuan', 1);

        $this->assertDatabaseHas('persetujuan', 
        [
            'id_pengajuan' => $pengajuan->id,
            'role' => 'bendahara',
        ]);
    }

    public function test_update_status_persetujuan_awal_pengajuan_pinjaman_dengan_jaminan_gagal_file_belum_verifikasi()
    {
        $user = User::factory()->create();
        $jaminan = Jaminan::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create([
            'jaminan' => 'ada',
        ]);
        $skema_pinjaman->daftarJaminan()->attach($jaminan->id);
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $pengajuan->jaminan()->attach($jaminan->id, [
            'file_jaminan' => 'jaminan/ktp.pdf',
            'status_verifikasi' => 'menunggu',
        ]);

        $this->assertDatabaseHas('pengajuan_jaminan', [
            'id_pengajuan' => $pengajuan->id,
            'id_jaminan' => $jaminan->id,
            'status_verifikasi' => 'menunggu',
        ]);

        $response = $this->patch("pengajuan_pinjaman/updateStatus/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman', [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $response = $this->patch("pengajuan_pinjaman/teruskan/{$pengajuan->id}");

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'status_pengajuan' => 'verifikasi',
        ]);

        $this->assertDatabaseCount('persetujuan', 0);
    }
}
