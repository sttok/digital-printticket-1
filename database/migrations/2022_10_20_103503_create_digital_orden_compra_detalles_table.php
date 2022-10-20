<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalOrdenCompraDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_orden_compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('digital_orden_compra_id')->unsigned();
            $table->bigInteger('order_child_id')->unsigned();
            $table->bigInteger('endosado_id')->unsigned()->nullable();
            $table->bigInteger('digital_id')->unsigned();
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
        Schema::dropIfExists('digital_orden_compra_detalles');
    }
}
