<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hospitalizaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paciente_id');
            $table->dateTime('fecha_ingreso');
            $table->string('motivo', 150);
            $table->string('acompanante', 100)->nullable();
            $table->string('hospital', 150)->nullable();
            $table->unsignedBigInteger('medico_id')->nullable();
            $table->string('medico_responsable')->nullable();
            $table->string('clinica', 100)->nullable();
            $table->enum('estado', ['Activo', 'Pendiente de admisión', 'Egreso'])->default('Pendiente de admisión');
            $table->timestamps();

            // Relaciones
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('hospitalizaciones');
    }
};
