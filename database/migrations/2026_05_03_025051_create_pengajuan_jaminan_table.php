<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuanJaminanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_jaminan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pengajuan')->unsigned();
            $table->bigInteger('id_jaminan')->unsigned();
            $table->string('file_jaminan');
            $table->enum('status_verifikasi', ['menunggu', 'verifikasi', 'ditolak']);
            $table->string('keterangan', 200)->nullable();

            $table->foreign('id_pengajuan')->references('id')
                    ->on('pengajuan_pinjaman')->onDelete('cascade');
            $table->foreign('id_jaminan')->references('id')
                    ->on('jaminan')->onDelete('cascade');

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
        Schema::dropIfExists('pembayaran');
    }
}
