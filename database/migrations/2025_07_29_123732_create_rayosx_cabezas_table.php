<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
  
   Schema::create('rayosx_cabezas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('craneo_anterior_posterior')->default(false);
    $table->boolean('craneo_lateral')->default(false);
    $table->boolean('waters')->default(false);
    $table->boolean('waters_lateral')->default(false);
    $table->boolean('conductos_auditivos')->default(false);
    $table->boolean('cavum')->default(false);
    $table->boolean('senos_paranasales')->default(false);
    $table->boolean('silla_turca')->default(false);
    $table->boolean('huesos_nasales')->default(false);
    $table->boolean('atm_tm')->default(false);
    $table->boolean('mastoides')->default(false);
    $table->boolean('mandibula')->default(false);
   
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_cabezas');
    }
};
