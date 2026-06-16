<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_angsuran')->unsigned();
            $table->enum('jenis_pembayaran', ['manual', 'auto-debet']);
            $table->date('tanggal_bayar');
            $table->integer('jumlah_bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['verifikasi', 'ditolak', 'sukses']);
            $table->string('catatan', 200)->nullable();

            $table->foreign('id_angsuran')->references('id')
                    ->on('angsuran')->onDelete('cascade');

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
