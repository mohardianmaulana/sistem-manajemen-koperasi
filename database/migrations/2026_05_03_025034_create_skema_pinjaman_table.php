<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkemaPinjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skema_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->integer('min_nominal');
            $table->integer('max_nominal');
            $table->integer('min_tenor');
            $table->integer('max_tenor');
            $table->decimal('bunga', 5, 2);
            $table->enum('jaminan', ['tidak', 'ada']);
            $table->string('deskripsi', 200);
            $table->enum('status', ['nonaktif', 'aktif']);

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
        Schema::dropIfExists('skema_pinjaman');
    }
}
