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
Schema::create('rayosx_abdomens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('abdomen_simple')->default(false);
    $table->boolean('abdomen_agudo')->default(false);
    $table->boolean('abdomen_erecto')->default(false);
    $table->boolean('abdomen_decubito')->default(false);
    $table->text('otros')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_abdomens');
    }
};
