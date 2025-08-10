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
 Schema::create('rayosx_toraxs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
    $table->boolean('torax_posteroanterior_pa')->default(false);
    $table->boolean('torax_anteroposterior_ap')->default(false);
    $table->boolean('torax_lateral')->default(false);
    $table->boolean('torax_oblicuo')->default(false);
    $table->boolean('torax_superior')->default(false);
    $table->boolean('torax_inferior')->default(false);
    $table->boolean('costillas_superiores')->default(false);
    $table->boolean('costillas_inferiores')->default(false);
    $table->boolean('esternon_frontal')->default(false);
    $table->boolean('esternon_lateral')->default(false);
    $table->text('otros')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rayosx_toraxs');
    }
};
