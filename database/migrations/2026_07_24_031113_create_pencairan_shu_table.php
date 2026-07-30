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

            $table->string('kode_pencairan')->unique();

            $table->foreignId('id_shu_anggota')
                ->constrained('shu_anggota')
                ->cascadeOnDelete();

            $table->bigInteger('nominal_pencairan');

            $table->date('tanggal_pencairan');

            $table->string('bukti')->nullable();

            $table->enum('status', [
                'siap_dicairkan',
                'dicairkan',
                'gagal'
            ])->default('siap_dicairkan');

            $table->text('keterangan')->nullable();

            $table->foreignId('dicairkan_oleh')
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
