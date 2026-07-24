<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePencairanShuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pencairan_shu', function (Blueprint $table) {
              $table->id();

        $table->foreignId('id_shu_anggota')
              ->constrained('shu_anggota')
              ->cascadeOnDelete();

        $table->date('tanggal_pengajuan')->nullable();
        $table->date('tanggal_persetujuan')->nullable();
        $table->date('tanggal_pencairan')->nullable();

        $table->enum('status', [
            'belum_diajukan',
            'menunggu',
            'disetujui',
            'ditolak',
            'dicairkan'
        ])->default('belum_diajukan');

        $table->text('keterangan')->nullable();

        // Jika ingin mengetahui siapa yang menyetujui
        $table->foreignId('disetujui_oleh')
              ->nullable()
              ->constrained('users')
              ->nullOnDelete();

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
        Schema::dropIfExists('pencairan_shu');
    }
}
