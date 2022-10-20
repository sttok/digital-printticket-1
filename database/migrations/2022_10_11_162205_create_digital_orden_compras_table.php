<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_orden_compras', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->bigInteger('evento_id')->unsigned()->nullable();
            $table->bigInteger('vendedor_id')->unsigned()->nullable();
            $table->bigInteger('cliente_id')->unsigned()->nullable();
            $table->integer('cantidad_entradas')->nullable();
            $table->boolean('metodo_pago')->nullable();
            $table->bigInteger('abonado')->nullable();
            $table->bigInteger('total')->nullable();
            $table->boolean('estado_venta')->default(1);
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
        Schema::dropIfExists('digital_orden_compras');
    }
}
