<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() 
{
    Schema::create('medicos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('numero_identidad', 13)->unique();
        $table->decimal('sueldo', 7, 2)->nullable();
        $table->string('especialidad');
        $table->string('telefono')->unique();
        $table->string('correo')->unique();
        $table->date('fecha_nacimiento');
        $table->date('fecha_ingreso');
        $table->string('genero');
        $table->text('observaciones')->nullable();
        $table->string('foto')->nullable();
        $table->boolean('estado')->default(true);
        $table->timestamps();

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};