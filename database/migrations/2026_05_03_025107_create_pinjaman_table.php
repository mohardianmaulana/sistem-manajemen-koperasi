<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePinjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pengajuan')->unsigned();
            $table->integer('jumlah_disetujui');
            $table->integer('jumlah_bunga');
            $table->integer('total_pinjaman');
            $table->date('tanggal_disetujui');
            $table->enum('status_pinjaman', ['belum_aktif', 'aktif', 'selesai']);

            $table->foreign('id_pengajuan')->references('id')
                    ->on('pengajuan_pinjaman')->onDelete('cascade');

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
        Schema::dropIfExists('pinjaman');
    }
}
