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
            $table->boolean('arteriograma')->default(false);
            $table->boolean('histerosalpingograma')->default(false);
            $table->boolean('colecistograma')->default(false);
            $table->boolean('fistulograma')->default(false);
            $table->boolean('artrograma')->default(false);
            $table->text('otros')->nullable();
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
