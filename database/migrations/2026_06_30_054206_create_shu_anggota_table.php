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
            $table->bigInteger('shu_anggota')->unsigned();
            $table->dateTime('tanggal')->nullable();
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
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
