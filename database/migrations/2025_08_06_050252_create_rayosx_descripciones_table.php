<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rayosx_descripciones', function (Blueprint $table) {
            $table->id();
            $table->string('paciente'); // o paciente_id si tienes relación
            $table->string('examen');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rayosx_descripciones');
    }
};
