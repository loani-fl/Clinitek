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
            $table->string('motivo');
            $table->string('medico_responsable');
            $table->enum('estado', ['Activo', 'Pendiente de admisión', 'Egreso'])->default('Pendiente de admisión');
            $table->timestamps();

            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('hospitalizaciones');
    }
};
