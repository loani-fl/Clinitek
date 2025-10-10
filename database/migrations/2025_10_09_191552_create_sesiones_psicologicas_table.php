<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesiones_psicologicas', function (Blueprint $table) {
            $table->id();
            // Relación con pacientes
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')
                ->references('id')
                ->on('pacientes')
                ->onDelete('cascade');

            // Relación con médicos (psicólogo que atiende)
            $table->unsignedBigInteger('medico_id');
            $table->foreign('medico_id')
                ->references('id')
                ->on('medicos')
                ->onDelete('cascade');

            // Datos de la sesión
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->text('motivo_consulta');
            $table->string('tipo_examen');
            $table->text('resultado');
            $table->text('observaciones')->nullable();

            // Archivo adjunto opcional
            $table->string('archivo_resultado')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones_psicologicas');
    }
};
