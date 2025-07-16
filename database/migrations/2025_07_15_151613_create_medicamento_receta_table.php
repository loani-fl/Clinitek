<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicamento_receta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade');
            $table->string('indicaciones', 500);
            $table->string('dosis', 255);      // agregar dosis
            $table->string('detalles', 500);   // agregar detalles de prescripción
            $table->timestamps();

            $table->unique(['receta_id', 'medicamento_id']); // evitar duplicados en una receta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamento_receta');
    }
};

