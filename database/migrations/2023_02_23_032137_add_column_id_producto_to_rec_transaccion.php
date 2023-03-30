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
        Schema::table('rec_transaccion', function (Blueprint $table) {
          $table->unsignedBigInteger('id_producto')->nullable()->after('id_accion');
          $table->foreign('id_producto')->nullable()->references('id')->on('de_producto');

          $table->integer('estado')->default(1)->after('descripcion'); // OK, Pendiente, Reembolso
        });
    }
};
