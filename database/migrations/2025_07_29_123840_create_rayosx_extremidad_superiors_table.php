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
 Schema::create('rayosx_extremidad_superiors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('clavicula_izquierda')->default(false);
    $table->boolean('clavicula_derecha')->default(false);
    $table->boolean('hombro_anterior')->default(false);
    $table->boolean('hombro_lateral')->default(false);
    $table->boolean('humero_proximal')->default(false);
    $table->boolean('humero_distal')->default(false);
    $table->boolean('codo_anterior')->default(false);
    $table->boolean('codo_lateral')->default(false);
    $table->boolean('antebrazo')->default(false);
    $table->boolean('muneca')->default(false);
    $table->boolean('mano')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_extremidad_superiors');
    }
};
