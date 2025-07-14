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

            // Relación con paciente
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');

            // Relación con medico
            $table->foreignId('medico_id')->constrained()->onDelete('cascade');

            // Información de la consulta
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('especialidad')->nullable();
            $table->text('motivo');
            $table->text('sintomas')->nullable();

            // Estado de la consulta
            $table->enum('estado', ['pendiente', 'realizada', 'cancelada'])->default('pendiente');

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
