<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');


            // Información de la consulta
            $table->string('genero')->nullable();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('especialidad')->nullable();
            $table->unsignedBigInteger('medico_id');
            $table->foreign('medico_id')->references('id')->on('medicos');

            $table->text('motivo');
            $table->text('sintomas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
