<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAngsuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pinjaman')->unsigned();
            $table->integer('angsuran_ke');
            $table->integer('jumlah_angsuran');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status_bayar', ['gagal_debet','belum_bayar', 'lunas']);

            $table->foreign('id_pinjaman')->references('id')
                    ->on('pinjaman')->onDelete('cascade');

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
        Schema::dropIfExists('angsuran');
    }
}
