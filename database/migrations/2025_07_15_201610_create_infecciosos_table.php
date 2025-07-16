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
    Schema::create('infecciosos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('diagnostico_id');
        $table->boolean('hiv_1_y_2')->default(false);
        $table->boolean('hepatitis_b')->default(false);
        $table->boolean('hepatitis_c')->default(false);
        $table->boolean('sifilis_vdrl_o_rpr')->default(false);
        $table->boolean('citomegalovirus_cmv')->default(false);
        $table->timestamps();

        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infecciosos');
    }
};
