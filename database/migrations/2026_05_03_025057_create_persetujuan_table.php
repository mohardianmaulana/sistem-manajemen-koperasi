<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersetujuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pengajuan')->unsigned();
            $table->enum('role', ['bendahara', 'wadir', 'ketua']);
            $table->bigInteger('disetujui_oleh')->unsigned()->nullable();
            $table->enum('status', ['menunggu', 'ditolak', 'disetujui']);
            $table->date('tanggal_disetujui')->nullable();
            $table->string('catatan', 200)->nullable();

            $table->foreign('id_pengajuan')->references('id')
                    ->on('pengajuan_pinjaman')->onDelete('cascade');
            $table->foreign('disetujui_oleh')->references('id')
                    ->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('persetujuan');
    }
}
