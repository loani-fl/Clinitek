<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pacientes_rayosxs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('identidad', 13)->unique();
            $table->date('fecha_orden');
            $table->date('fecha_nacimiento');
            $table->unsignedInteger('edad')->nullable();
            $table->string('telefono'); // ahora es obligatorio
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pacientes_rayosxs');
    }
};
