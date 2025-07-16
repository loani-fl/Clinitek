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
    Schema::create('bioquimicos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('urea')->default(false);
        $table->boolean('bun')->default(false);
        $table->boolean('creatinina')->default(false);
        $table->boolean('acido_urico')->default(false);
        $table->boolean('glucosa')->default(false);
        $table->boolean('glucosa_post_prandial_2h')->default(false);
        $table->boolean('c_tolencia_glucosa_2h')->default(false);
        $table->boolean('c_tolencia_glucosa_4h')->default(false);
        $table->boolean('bilirrubina_total_y_fracciones')->default(false);
        $table->boolean('proteinas_totales')->default(false);
        $table->boolean('albumina_globulina')->default(false);
        $table->boolean('electroforesis_proteinas')->default(false);
        $table->boolean('cistatina_c_creatinina_tfg')->default(false);
        $table->boolean('diabetes_gestacional')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bioquimicos');
    }
};
