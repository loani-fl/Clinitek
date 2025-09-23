<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergencias', function (Blueprint $table) {
            $table->id();

            // Documentación
            //$table->boolean('documentado')->default(true);
             $table->boolean('documentado')->default(true);
            $table->string('nombres')->nullable();
             $table->string('apellidos')->nullable();
            $table->string('identidad')->nullable();
            $table->integer('edad')->nullable();
            $table->enum('sexo', ['M','F'])->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();

            // Indocumentado
            $table->string('codigo_temporal')->nullable();
            $table->string('foto')->nullable();

            // Datos de la emergencia
            $table->dateTime('fecha_hora');
            //$table->enum('evento', ['trauma', 'medico', 'obstetrico', 'pediatrico', 'otro']);
            //$table->string('lugar')->nullable();
            $table->text('motivo')->nullable();

            // Signos vitales
            $table->string('pa')->nullable(); // ejemplo: 120/80
            $table->integer('fc')->nullable();
            //$table->integer('fr')->nullable();
            $table->decimal('temp', 4,1)->nullable();
            //$table->integer('sat')->nullable();

            // Estado al ingreso
           // $table->enum('conciencia', ['consciente','inconsciente'])->nullable();
            //$table->enum('triage', ['rojo','amarillo','verde'])->nullable();

            // Atención inmediata → booleanos
           // $table->boolean('oxigeno')->default(false);
            //$table->boolean('canalizacion')->default(false);
            //$table->boolean('hemorragia')->default(false);
            //$table->boolean('reanimacion')->default(false);
            //$table->text('medicamentos')->nullable();

            // Responsable
            //$table->string('responsable')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergencias');
    }
};

