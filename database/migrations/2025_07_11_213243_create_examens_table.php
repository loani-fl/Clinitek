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
    Schema::create('examens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
        $table->foreignId('consulta_id')->constrained()->onDelete('cascade');
        $table->string('nombre');  // nombre del examen
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
