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
    Schema::create('marcadores_tumorales', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('af_proteina')->default(false);
        $table->boolean('ac_embrionario')->default(false);
        $table->boolean('ca125')->default(false);
        $table->boolean('he4')->default(false);
        $table->boolean('indice_roma')->default(false);
        $table->boolean('ca15_3')->default(false);
        $table->boolean('ca19_9')->default(false);
        $table->boolean('ca72_4')->default(false);
        $table->boolean('cyfra_21_1')->default(false);
        $table->boolean('beta_2_microglobulina')->default(false);
        $table->boolean('enolasa_neuroespecifica')->default(false);
        $table->boolean('antigeno_prostatico_psa')->default(false);
        $table->boolean('psa_libre')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marcadores_tumorales');
    }
};
