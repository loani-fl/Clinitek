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
    Schema::create('hematologia', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('hemograma_completo')->default(false);
        $table->boolean('frotis_en_sangre_periferica')->default(false);
        $table->boolean('reticulocitos')->default(false);
        $table->boolean('eritrosedimentacion')->default(false);
        $table->boolean('grupo_sanguineo')->default(false);
        $table->boolean('p_coombs_directa')->default(false);
        $table->boolean('p_coombs_indirecta')->default(false);
        $table->boolean('plasmodium_gota_gruesa')->default(false);
        $table->boolean('plasmodium_anticuerpos')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hematologia');
    }
};
