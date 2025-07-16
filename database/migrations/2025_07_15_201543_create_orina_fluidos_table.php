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
    Schema::create('orina_fluidos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('examen_general_orina')->default(false);
        $table->boolean('cultivo_orina')->default(false);
        $table->boolean('orina_24_horas')->default(false);
        $table->boolean('prueba_embarazo')->default(false);
        $table->boolean('liquido_cefalorraquideo')->default(false);
        $table->boolean('liquido_pleural')->default(false);
        $table->boolean('liquido_peritoneal')->default(false);
        $table->boolean('liquido_articular')->default(false);
        $table->boolean('espermograma')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orina_fluidos');
    }
};
