<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('examens', function (Blueprint $table) {
            // Agregar columna 'precio' si no existe
            if (!Schema::hasColumn('examens', 'precio')) {
                $table->decimal('precio', 10, 2)->default(0)->after('nombre');
            }

            // Agregar columna 'codigo' si no existe
            if (!Schema::hasColumn('examens', 'codigo')) {
                $table->string('codigo')->unique()->after('precio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('examens', function (Blueprint $table) {
            if (Schema::hasColumn('examens', 'precio')) {
                $table->dropColumn('precio');
            }
            if (Schema::hasColumn('examens', 'codigo')) {
                $table->dropColumn('codigo');
            }
        });
    }
};
