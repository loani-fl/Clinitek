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
        Schema::create('farmacias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('departamento', 100);
            $table->string('ciudad', 100);
            $table->string('direccion', 255)->nullable(); // cambiado de ubicacion a direccion
            $table->string('telefono', 8)->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('descuento', 5, 2)->nullable();
            $table->string('foto')->nullable();
            $table->string('pagina_web', 100)->nullable(); // Campo para la página web
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmacias');
    }
};
