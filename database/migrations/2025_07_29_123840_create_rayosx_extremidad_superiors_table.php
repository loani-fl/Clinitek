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
            $table->boolean('clavicula')->default(false);
            $table->boolean('hombro')->default(false);
            $table->boolean('humero')->default(false);
            $table->boolean('codo')->default(false);
            $table->boolean('antebrazo')->default(false);
            $table->boolean('muneca')->default(false);
            $table->boolean('mano')->default(false);
            $table->text('otros')->nullable();
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
