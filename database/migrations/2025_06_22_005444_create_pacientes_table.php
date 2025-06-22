<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->string('identidad', 13); // agregado
            $table->date('fecha_nacimiento');
            $table->string('telefono', 8);
            $table->string('direccion', 300);
            $table->string('correo', 50)->nullable();
            $table->string('tipo_sangre', 3)->nullable(); // Ejemplo: "A+", "O-", etc.
            $table->string('padecimientos', 200);
            $table->string('medicamentos', 200);
            $table->string('historial_clinico', 200);
            $table->string('alergias', 200);
            $table->string('historial_quirurgico', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
