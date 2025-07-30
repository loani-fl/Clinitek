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
            $table->boolean('cervical')->default(false);
            $table->boolean('dorsal')->default(false);
            $table->boolean('lumbar')->default(false);
            $table->boolean('sacro_coxis')->default(false);
            $table->boolean('pelvis')->default(false);
            $table->boolean('escoliosis')->default(false);
            $table->text('otros')->nullable();
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
