<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterJenisSimpananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_jenis_simpanan', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_jenis_simpanan', ['Simpanan Wajib', 'Simpanan Sukarela']);
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_berakhir');
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
        Schema::dropIfExists('master_jenis_simpanan');
    }
}
