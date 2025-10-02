<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergencias', function (Blueprint $table) {
            $table->id();

            // Relación con paciente si está documentado
            $table->foreignId('paciente_id')->nullable()
                  ->constrained('pacientes')
                  ->onDelete('set null');

            // Lógica documentado / indocumentado
            $table->boolean('documentado')->default(true);

            // Solo si es indocumentado
            $table->string('foto')->nullable();
            $table->string('direccion')->nullable();

            // Datos de la emergencia
            $table->date('fecha');       // YYYY-MM-DD
            $table->time('hora');        // HH:MM:SS
            $table->text('motivo')->nullable();

            // Signos vitales
            $table->string('pa')->nullable(); // presión arterial: 120/80
            $table->integer('fc')->nullable(); // frecuencia cardíaca
            $table->decimal('temp', 4,1)->nullable(); // temperatura

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergencias');
    }
};
