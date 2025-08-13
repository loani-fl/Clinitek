<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRayosxOrdersTable extends Migration
{
   public function up()
{
    Schema::create('rayosx_orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('paciente_id');
        $table->string('paciente_tipo')->nullable();
        $table->date('fecha');
        $table->integer('edad')->nullable();
        $table->string('identidad')->nullable();
        $table->string('nombres')->nullable();
        $table->string('apellidos')->nullable();
        $table->text('datos_clinicos')->nullable();
        $table->decimal('total_precio', 8, 2)->default(0);
        $table->enum('estado', ['Pendiente', 'Realizado'])->default('Pendiente');
        $table->timestamps();

        $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
    });
}


    public function down()
    {
        Schema::dropIfExists('rayosx_orders');
    }
}
