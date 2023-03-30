<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rec_transaccion', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('gp_usuario');

            $table->unsignedBigInteger('id_accion')->nullable();
            $table->foreign('id_accion')->nullable()->references('id')->on('rec_accion');

            $table->boolean('tipo')->default(true); // entrada o salida

            $table->integer('credito')->default(0);
            $table->string('nombre')->nullable();
            $table->string('descripcion',500)->nullable();

            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('rec_transacciones');
    }
};
