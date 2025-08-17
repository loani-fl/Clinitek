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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('medico_id')->nullable()->constrained('medicos')->onDelete('set null');

            // Datos del pago
            $table->string('servicio')->nullable(); // Consulta médica / Rayos X
            $table->text('descripcion')->nullable(); // Exámenes seleccionados
            $table->decimal('cantidad', 10, 2)->default(0); // Precio

            $table->string('metodo_pago'); // efectivo, tarjeta
            $table->dateTime('fecha');

            // Solo si es tarjeta
            $table->string('nombre_titular')->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->string('fecha_expiracion')->nullable();

            // Origen del pago
            $table->string('origen'); // 'consulta' o 'rayosx'
            $table->unsignedBigInteger('referencia_id'); // ID de consulta o rayosx asociado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
