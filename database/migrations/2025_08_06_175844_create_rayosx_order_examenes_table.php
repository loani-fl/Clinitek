<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRayosxOrderExamenesTable extends Migration
{
    public function up()
    {
        Schema::create('rayosx_order_examenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rayosx_order_id');
            $table->string('examen_codigo');
            $table->timestamps();

            $table->foreign('rayosx_order_id')->references('id')->on('rayosx_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rayosx_order_examenes');
    }
}
