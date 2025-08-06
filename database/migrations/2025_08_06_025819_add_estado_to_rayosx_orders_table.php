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
        Schema::table('rayosx_orders', function (Blueprint $table) {
            // Estado de la orden (Pendiente o Realizado)
            $table->enum('estado', ['Pendiente', 'Realizado'])->default('Pendiente')->after('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
