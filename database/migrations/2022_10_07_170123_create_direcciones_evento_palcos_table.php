<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesEventoPalcosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones_evento_palcos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('direccion_evento_id')->unsigned();
            $table->bigInteger('direccion_entrada_id')->unsigned();
            $table->integer('palco');
            $table->string('path');
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
        Schema::dropIfExists('direcciones_evento_palcos');
    }
}
