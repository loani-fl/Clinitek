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
        Schema::create('diagnosticos', function (Blueprint $table) {

            $table->id();

            // Relación con la consulta
            $table->unsignedBigInteger('consulta_id');
            $table->foreign('consulta_id')
                ->references('id')
                ->on('consultas')
                ->onDelete('cascade');

            // Relación con el paciente
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')
                ->references('id')
                ->on('pacientes')
                ->onDelete('cascade');

            // Campos del diagnóstico
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
