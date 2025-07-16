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
    Schema::create('perfil_anemias', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('hierro_serico')->default(false);
        $table->boolean('capacidad_fijacion_hierro')->default(false);
        $table->boolean('transferrina')->default(false);
        $table->boolean('ferritina')->default(false);
        $table->boolean('vitamina_b12')->default(false);
        $table->boolean('acido_folico')->default(false);
        $table->boolean('eritropoyetina')->default(false);
        $table->boolean('haptoglobina')->default(false);
        $table->boolean('electroforesis_hemoglobina')->default(false);
        $table->boolean('glucosa_6_fosfato')->default(false);
        $table->boolean('fragilidad_osmotica_hematias')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')
              ->references('id')
              ->on('diagnosticos')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_anemias');
    }
};
