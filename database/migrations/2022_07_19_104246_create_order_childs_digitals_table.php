<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderChildsDigitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_childs_digitals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_id');
            $table->bigInteger('evento_id');
            $table->bigInteger('zona_id');
            $table->bigInteger('order_child_id');
            $table->text('url')->nullable();
            $table->text('provider')->nullable();
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
        Schema::dropIfExists('order_childs_digitals');
    }
}
