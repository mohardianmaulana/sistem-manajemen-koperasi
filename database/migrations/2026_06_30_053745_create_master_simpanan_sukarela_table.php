<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSimpananSukarelaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     
     */
    public function up()
    {
        Schema::create('master_simpanan_sukarela', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nilai')->unsigned();
            $table->date('periode');
            $table->year('tahun');
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'selesai', 'tidak berhasil'])->default('pending');
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
        Schema::dropIfExists('master_simpanan_sukarela');
    }
}
