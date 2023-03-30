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
        Schema::create('rec_accion', function (Blueprint $table) {
            $table->id();
            $table->string('token',100)->unique();
            $table->string('nombre');
            $table->string('descripcion',500)->nullable();
            // $table->string('imagen')->nullable();

            // Cuanto dara al usuario de credito
            $table->integer('credito')->default(0);

            // Es ilimitado o tiene un limite
            $table->boolean('stock_ilimitado')->default(false);
            $table->integer('stock')->nullable(0);

            // Cantidad entregada - interno n+1
            $table->integer('cant_entregada')->nullable();

            // Cantidad por usuario limite
            $table->boolean('cant_por_usuario_ilimitado')->default(false);
            $table->integer('cant_por_usuario')->nullable();



            $table->json('assets')->nullable();
            $table->json('config')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('gp_usuario');
            $table->integer('estado')->default(1);
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
        Schema::dropIfExists('rec_accion');
    }
};
