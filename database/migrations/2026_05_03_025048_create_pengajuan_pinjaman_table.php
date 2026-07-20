<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuanPinjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_anggota')->unsigned();
            $table->bigInteger('id_skema_pinjaman')->unsigned();
            $table->integer('jumlah_pengajuan');
            $table->integer('lama_angsuran');
            $table->date('tanggal_pengajuan');
            $table->enum('status_pengajuan', 
                        ['menunggu', 'persetujuan_awal', 'persetujuan_akhir',
                        'pencairan', 'disetujui', 'ditolak',
                        'revisi', 'verifikasi']);
            $table->string('no_hp');
            $table->string('no_ktp');
            $table->string('no_rekening');
            $table->string('alamat');
            $table->string('nama_istri_suami');
            $table->string('dokumen_ttd');

            $table->foreign('id_anggota')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('id_skema_pinjaman')->references('id')
                    ->on('skema_pinjaman')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_pinjaman');
    }
}
