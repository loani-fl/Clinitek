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
   Schema::create('rayosx_columna_pelvis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('columna_cervical_lateral')->default(false);
    $table->boolean('columna_cervical_anteroposterior')->default(false);
    $table->boolean('columna_dorsal_lateral')->default(false);
    $table->boolean('columna_dorsal_anteroposterior')->default(false);
    $table->boolean('columna_lumbar_lateral')->default(false);
    $table->boolean('columna_lumbar_anteroposterior')->default(false);
    $table->boolean('sacro_coxis')->default(false);
    $table->boolean('pelvis_anterior_posterior')->default(false);
    $table->boolean('pelvis_oblicua')->default(false);
    $table->boolean('escoliosis')->default(false);
  
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_columna_pelvis');
    }
};
