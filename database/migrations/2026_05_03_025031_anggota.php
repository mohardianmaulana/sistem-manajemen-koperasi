<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Anggota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_role')->unsigned();
            $table->string('nip', 20);
            $table->string('username', 50);
            $table->string('password');
            $table->string('jabatan');
            $table->bigInteger('id_unit')->unsigned();

            $table->foreign('id_role')->references('id')->on('role')->onDelete('cascade');
            $table->foreign('id_unit')->references('id')->on('unit')->onDelete('cascade');

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
        Schema::dropIfExists('anggota');
    }
}
