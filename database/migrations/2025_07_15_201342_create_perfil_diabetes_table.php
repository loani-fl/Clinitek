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
    Schema::create('perfil_diabetes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('peptido_c')->default(false);
        $table->boolean('indice_peptidico')->default(false);
        $table->boolean('insulina')->default(false);
        $table->boolean('homa_ir')->default(false);
        $table->boolean('homa_ir_post_prandial')->default(false);
        $table->boolean('fructosamina')->default(false);
        $table->boolean('hemoglobina_glicosilada')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_diabetes');
    }
};
