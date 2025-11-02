<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('controles_prenatales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            
            // Datos obstétricos
            $table->date('fecha_ultima_menstruacion');
            $table->date('fecha_probable_parto');
            $table->integer('semanas_gestacion');
            $table->integer('numero_gestaciones');
            $table->integer('numero_partos');
            $table->integer('numero_abortos');
            $table->integer('numero_hijos_vivos');
            $table->string('tipo_partos_anteriores')->nullable();
            $table->text('complicaciones_previas')->nullable();
            
            // Antecedentes médicos
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('alergias')->nullable();
            $table->text('cirugias_previas')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('habitos')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            
            // Datos del control prenatal actual
            $table->date('fecha_control');
            $table->string('presion_arterial');
            $table->integer('frecuencia_cardiaca_materna');
            $table->decimal('temperatura', 4, 1);
            $table->decimal('peso_actual', 5, 2);
            $table->decimal('altura_uterina', 4, 1)->nullable();
            $table->integer('latidos_fetales')->nullable();
            $table->string('movimientos_fetales')->nullable();
            $table->enum('edema', ['ninguno', 'leve', 'moderado', 'severo'])->default('ninguno');
            $table->enum('presentacion_fetal', ['cefalica', 'podalica', 'transversa', 'no_determinada'])->nullable();
            $table->text('resultados_examenes')->nullable();
            $table->text('observaciones')->nullable();
            
            // Tratamientos y recomendaciones
            $table->text('suplementos')->nullable();
            $table->text('vacunas_aplicadas')->nullable();
            $table->text('indicaciones_medicas')->nullable();
            $table->date('fecha_proxima_cita')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controles_prenatales');
    }
};