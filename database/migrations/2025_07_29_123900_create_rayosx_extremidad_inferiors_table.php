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
   Schema::create('rayosx_extremidad_inferiors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
            $table->boolean('cadera')->default(false);
            $table->boolean('femur')->default(false);
            $table->boolean('rodilla')->default(false);
            $table->boolean('tibia')->default(false);
            $table->boolean('pie')->default(false);
            $table->boolean('calcaneo')->default(false);
            $table->text('otros')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_extremidad_inferiors');
    }
};
