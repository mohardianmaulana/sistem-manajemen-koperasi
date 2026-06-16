<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Pinjaman\Entities\Anggota;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
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
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 1500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
        ]);
    }

    public function test_create_pengajuan_pinjaman_gagal_jumlah_pengajuan_dan_lama_angsuran_kurang_dari_0()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_anggota' => $user->id,
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
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
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
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 1500000,
            'lama_angsuran' => 12,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1111',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_create_pengajuan_pinjaman_gagal_file_upload_tidak_sesuai_dengan_ketentuan()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $response = $this->post("pengajuan_pinjaman/store",
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 150000,
            'lama_angsuran' => 2,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => 'xxxxx',
            'path_dokumen' => 'xxxx',
        ]);

        $response->assertSessionHasErrors([
            'path_form_pinjaman',
            'path_dokumen',
        ]);

        $this->assertDatabaseCount('pengajuan_pinjaman', 0);
    }

    public function test_update_pengajuan_pinjaman_sukses()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 2000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
            'tanggal_pengajuan' => '2026-05-01',
            'jumlah_pengajuan' => 2000000,
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
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => -1,
            'lama_angsuran' => -1,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors([
            'jumlah_pengajuan',
            'lama_angsuran',
        ]);

        $this->assertDatabaseMissing('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
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
    }

    public function test_update_pengajuan_pinjaman_gagal_no_hp_no_ktp_dan_no_rekening_tidak_sesuai_ketentuan()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 2000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '08213@',
            'no_ktp' => '35100',
            'no_rekening' => '1234',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => UploadedFile::fake()->create('form.pdf', 100, 'application/pdf'),
            'path_dokumen' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors([
            'no_hp',
            'no_ktp',
            'no_rekening',
        ]);

        $this->assertDatabaseMissing('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
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
    }

    public function test_update_pengajuan_pinjaman_gagal_file_upload_tidak_sesuai_dengan_ketentuan()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);
        $response = $this->put("pengajuan_pinjaman/update/{$pengajuan->id}",
        [
            'tanggal_pengajuan' => '2025-05-01',
            'jumlah_pengajuan' => 2000000,
            'lama_angsuran' => 15,
            'status_pengajuan' => 'menunggu',
            'no_hp' => '082132945801',
            'no_ktp' => '3510090503040006',
            'no_rekening' => '1234567890',
            'alamat' => 'Jl. Banyuwangi',
            'nama_istri_suami' => 'Seseorang',
            'path_form_pinjaman' => 'xxxx',
            'path_dokumen' => 'xxxx',
        ]);

        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('pengajuan_pinjaman',
        [
            'id' => $pengajuan->id,
            'id_anggota' => $user->id,
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
            'path_form_pinjaman' => 'xxxx',
            'path_dokumen' => 'xxxx',
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
}
