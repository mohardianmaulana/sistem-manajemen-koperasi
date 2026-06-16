<?php

namespace Tests\Feature;

use App\Models\Core\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Entities\Pembayaran;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Pinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_pembayaran_auto_debet()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $total_pinjaman = $pengajuan->jumlah_pengajuan + $skema_pinjaman->bunga * $pengajuan->lama_angsuran;

        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'jumlah_disetujui' => $pengajuan->jumlah_pengajuan,
            'jumlah_bunga' => $skema_pinjaman->bunga * $pengajuan->lama_angsuran,
            'total_pinjaman' => $total_pinjaman,
            'tanggal_disetujui' => '2026-06-01',
            'status_pinjaman' => 'belum_aktif',
        ]);

        $angsuran = Angsuran::factory()->create([
            'id_pinjaman' => $pinjaman->id,
            'angsuran_ke' => 1,
            'jumlah_angsuran' => $total_pinjaman / $pengajuan->lama_angsuran,
            'tanggal_jatuh_tempo' => '2026-06-01',
            'status_bayar' => 'belum_bayar',
        ]);

        $response = $this->post("pembayaran/store_auto_debet", [
            'id_angsuran' => $angsuran->id,
            'jenis_pembayaran' => 'auto-debet',
            'tanggal_bayar' => '2026-05-01',
            'jumlah_bayar' => $angsuran->jumlah_angsuran,
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'sukses',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pembayaran', [
            'id_angsuran' => $angsuran->id,
            'status_pembayaran' => 'sukses',
        ]);

        $this->assertDatabaseHas('angsuran', [
            'id' => $angsuran->id,
            'status_bayar' => 'lunas',
        ]);
    }

    public function test_pembayaran_manual()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $total_pinjaman = $pengajuan->jumlah_pengajuan + $skema_pinjaman->bunga * $pengajuan->lama_angsuran;

        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'jumlah_disetujui' => $pengajuan->jumlah_pengajuan,
            'jumlah_bunga' => $skema_pinjaman->bunga * $pengajuan->lama_angsuran,
            'total_pinjaman' => $total_pinjaman,
            'tanggal_disetujui' => '2026-06-01',
            'status_pinjaman' => 'belum_aktif',
        ]);

        $angsuran = Angsuran::factory()->create([
            'id_pinjaman' => $pinjaman->id,
            'angsuran_ke' => 1,
            'jumlah_angsuran' => $total_pinjaman / $pengajuan->lama_angsuran,
            'tanggal_jatuh_tempo' => '2026-06-01',
            'status_bayar' => 'belum_bayar',
        ]);

        $response = $this->post("pembayaran/store_manual", [
            'id_angsuran' => $angsuran->id,
            'jenis_pembayaran' => 'manual',
            'tanggal_bayar' => '2026-05-01',
            'jumlah_bayar' => $angsuran->jumlah_angsuran,
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg'),
            'status_pembayaran' => 'verifikasi',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pembayaran', [
            'id_angsuran' => $angsuran->id,
            'status_pembayaran' => 'verifikasi',
        ]);
    } 

    public function test_update_status_pembayaran_manual()
    {
        $user = User::factory()->create();
        $skema_pinjaman = SkemaPinjaman::factory()->create();
        $pengajuan = PengajuanPinjaman::factory()->create([
            'id_anggota' => $user->id,
            'id_skema_pinjaman' => $skema_pinjaman->id,
        ]);

        $total_pinjaman = $pengajuan->jumlah_pengajuan + $skema_pinjaman->bunga * $pengajuan->lama_angsuran;

        $pinjaman = Pinjaman::factory()->create([
            'id_pengajuan' => $pengajuan->id,
            'jumlah_disetujui' => $pengajuan->jumlah_pengajuan,
            'jumlah_bunga' => $skema_pinjaman->bunga * $pengajuan->lama_angsuran,
            'total_pinjaman' => $total_pinjaman,
            'tanggal_disetujui' => '2026-06-01',
            'status_pinjaman' => 'belum_aktif',
        ]);

        $angsuran = Angsuran::factory()->create([
            'id_pinjaman' => $pinjaman->id,
            'angsuran_ke' => 1,
            'jumlah_angsuran' => $total_pinjaman / $pengajuan->lama_angsuran,
            'tanggal_jatuh_tempo' => '2026-06-01',
            'status_bayar' => 'belum_bayar',
        ]);

        $pembayaran = Pembayaran::factory()->create([
            'id_angsuran' => $angsuran->id,
            'jenis_pembayaran' => 'manual',
            'tanggal_bayar' => '2026-06-01',
            'jumlah_bayar' => $angsuran->jumlah_angsuran,
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg'),
            'status_pembayaran' => 'verifikasi',
        ]);
        
        $response = $this->patch("/pembayaran/verifikasi/{$pembayaran->id}", [
            'id_angsuran' => $angsuran->id,
            'status_pembayaran' => 'sukses',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'status_pembayaran' => 'sukses',
        ]);

        $this->assertDatabaseHas('angsuran', [
            'id' => $angsuran->id,
            'status_bayar' => 'lunas'
        ]);
    }
}
