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
       Schema::create('rayosx_estudios_especiales', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('arteriograma_simple')->default(false);
    $table->boolean('arteriograma_contraste')->default(false);
    $table->boolean('histerosalpingograma_simple')->default(false);
    $table->boolean('histerosalpingograma_contraste')->default(false);
    $table->boolean('colecistograma_simple')->default(false);
    $table->boolean('colecistograma_contraste')->default(false);
    $table->boolean('fistulograma_simple')->default(false);
    $table->boolean('fistulograma_contraste')->default(false);
    $table->boolean('artrograma_simple')->default(false);
    $table->boolean('artrograma_contraste')->default(false);
 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_estudios_especiales');
    }
};
