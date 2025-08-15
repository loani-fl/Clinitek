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
            $table->string('ruta'); // renombrado para ser consistente con el modelo
            $table->text('descripcion')->nullable(); // campo para la descripción
            $table->timestamps();
        
            $table->foreign('rayosx_order_examen_id')
                  ->references('id')->on('rayosx_order_examenes')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rayosx_order_examen_imagenes');
    }
}
