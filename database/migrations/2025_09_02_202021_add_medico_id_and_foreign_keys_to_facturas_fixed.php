<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            // Agregar las columnas ID que faltan
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->unsignedBigInteger('medico_id')->nullable();
            
            // Agregar claves foráneas
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropForeign(['medico_id']);
            $table->dropColumn(['paciente_id', 'medico_id']);
        });
    }
};