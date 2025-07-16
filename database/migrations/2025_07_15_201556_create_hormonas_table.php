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
    Schema::create('hormonas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('hormona_estimulante_tiroides_tsh')->default(false);
        $table->boolean('hormona_luteinizante_lh')->default(false);
        $table->boolean('hormona_foliculo_estimulante_fsh')->default(false);
        $table->boolean('cortisol')->default(false);
        $table->boolean('prolactina')->default(false);
        $table->boolean('testosterona')->default(false);
        $table->boolean('estradiol')->default(false);
        $table->boolean('progesterona')->default(false);
        $table->boolean('beta_hcg_embarazo')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hormonas');
    }
};
