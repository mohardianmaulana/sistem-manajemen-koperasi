<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePencairanSimpananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pencairan_simpanan', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pencairan')->unique();

            $table->bigInteger('nominal_pencairan');

            $table->text('alasan')->nullable();

            $table->enum('status', [
                'pending',
                'diverifikasi',
                'ditolak',
                'dicairkan',
                'gagal'
            ])->default('pending');

            $table->text('catatan')->nullable();

            $table->string('bukti_transfer')->nullable();

            $table->foreignId('id_anggota')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('id_verifikator')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('id_bendahara')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_pencairan')->nullable();

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
        Schema::dropIfExists('pencairan_simpanan');
    }
}
