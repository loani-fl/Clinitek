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
    Schema::create('inmunologia_autoinmunidad', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('iga')->default(false);
        $table->boolean('igg')->default(false);
        $table->boolean('igm')->default(false);
        $table->boolean('ige')->default(false);
        $table->boolean('complemento_c3')->default(false);
        $table->boolean('complemento_c4')->default(false);
        $table->boolean('vitamina_d')->default(false);
        $table->boolean('ac_antinucleares')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmunologia_autoinmunidad');
    }
};
