<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkemaJaminanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skema_jaminan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_skema_pinjaman')->unsigned();
            $table->bigInteger('id_jaminan')->unsigned();

            $table->foreign('id_skema_pinjaman')->references('id')
                    ->on('skema_pinjaman')->onDelete('cascade');
            $table->foreign('id_jaminan')->references('id')
                    ->on('jaminan')->onDelete('cascade');

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
        Schema::dropIfExists('skema_jaminan');
    }
}
