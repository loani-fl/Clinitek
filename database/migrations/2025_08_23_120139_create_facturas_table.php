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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura')->unique();
            $table->string('paciente_nombre');
            $table->string('paciente_identidad');
            $table->string('medico_nombre')->nullable();
            $table->string('especialidad')->nullable();
            $table->enum('tipo', ['consulta', 'rayos_x']);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->json('descripcion'); // Para almacenar los servicios/exámenes
            $table->decimal('total', 10, 2);
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};