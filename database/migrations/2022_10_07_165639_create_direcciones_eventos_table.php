<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones_eventos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evento_id');
            $table->bigInteger('entrada_id');
            $table->bigInteger('usuario_id');
            $table->bigInteger('direccion_usuario')->unsigned();
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
        Schema::dropIfExists('direcciones_eventos');
    }
}
