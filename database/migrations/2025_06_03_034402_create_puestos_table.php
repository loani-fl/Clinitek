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
    Schema::create('puestos', function (Blueprint $table) {
    $table->id();

  $table->string('nombre');       // Nombre del puesto
    $table->string('codigo')->unique();       // Código del puesto
   $table->string('area')->nullable();

    $table->decimal('sueldo', 10, 2);         // Sueldo del puesto
    $table->text('funcion');                 // Función del puesto
   

    $table->timestamps();
});

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puestos');
    }
};
