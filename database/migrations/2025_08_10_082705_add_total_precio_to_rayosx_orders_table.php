<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->decimal('total_precio', 10, 2)->default(0)->after('datos_clinicos');
            // Ajusta el after() según dónde quieras ponerlo, o quítalo si no importa
        });
    }

    public function down(): void
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropColumn('total_precio');
        });
    }
};

