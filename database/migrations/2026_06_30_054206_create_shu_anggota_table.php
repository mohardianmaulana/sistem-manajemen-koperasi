<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShuAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shu_anggota', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shu_simpanan');
            $table->bigInteger('shu_pinjaman');
            $table->bigInteger('jasa_pengurus');
            $table->bigInteger('pajak');
            $table->bigInteger('shu_anggota');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->foreignId('id_anggota')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('shu_anggota');
    }
}
