<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSisaHasilUsahaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sisa_hasil_usaha', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jasa_simpanan')->unsigned();
            $table->bigInteger('jasa_pinjaman')->unsigned();
            $table->bigInteger('dana_cadangan')->unsigned();
            $table->bigInteger('jasa_pengurus')->unsigned();
            $table->bigInteger('dana_sosial')->unsigned();
            $table->bigInteger('total_shu')->unsigned();
            $table->date('periode_awal');
            $table->date('periode_akhir');
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
        Schema::dropIfExists('sisa_hasil_usaha');
    }
}
