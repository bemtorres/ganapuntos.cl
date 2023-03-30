<?php

use App\Models\GP\Config;
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
        Schema::create('gp_config', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('assets_coin')->nullable();

            // $table->json('integrations')->nullable();
            // $table->json('info')->nullable();
            $table->timestamps();
        });

        $c = new Config();
        $c->nombre = 'Ganapuntos.cl';
        $c->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gp_config');
    }
};
