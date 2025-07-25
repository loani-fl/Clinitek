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
        Schema::create('listaempleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('identidad')->unique();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo')->unique();
            $table->date('fecha_ingreso');
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->enum('estado_civil', ['Soltero', 'Casado', 'Divorciado', 'Viudo'])->nullable();
            $table->foreignId('puesto_id')->constrained()->onDelete('cascade');
            $table->string('area');
            $table->string('turno_asignado');
            $table->decimal('salario', 10, 2)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->text('observaciones')->nullable();
            $table->string('foto')->nullable(); // ✅ Aquí movida antes de timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listaempleados');
    }
};
