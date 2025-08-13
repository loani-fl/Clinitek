<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRayosxExamenImagenesTable extends Migration
{
    public function up()
    {
        Schema::create('rayosx_order_examen_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rayosx_order_examen_id');
            $table->string('imagen_ruta');
            $table->timestamps();

            $table->foreign('rayosx_order_examen_id')->references('id')->on('rayosx_order_examenes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rayosx_order_examen_imagenes');
    }
}
