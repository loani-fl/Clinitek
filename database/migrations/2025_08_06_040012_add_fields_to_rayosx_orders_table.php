<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('rayosx_orders', function (Blueprint $table) {
        // Eliminar FK y el índice único de diagnostico_id si existen
        try {
            $table->dropForeign(['diagnostico_id']);
        } catch (\Exception $e) {}

        try {
            $table->dropUnique('rayosx_orders_diagnostico_id_unique');
        } catch (\Exception $e) {}

        // Volver a agregar la FK sin unique (opcional)
        $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('cascade');

        // Agregar columnas nuevas
        if (!Schema::hasColumn('rayosx_orders', 'paciente_id')) {
            $table->unsignedBigInteger('paciente_id')->nullable()->after('diagnostico_id');
        }
        if (!Schema::hasColumn('rayosx_orders', 'edad')) {
            $table->integer('edad')->nullable()->after('fecha');
        }
        if (!Schema::hasColumn('rayosx_orders', 'identidad')) {
            $table->string('identidad')->nullable()->after('edad');
        }
        if (!Schema::hasColumn('rayosx_orders', 'nombres')) {
            $table->string('nombres')->nullable()->after('identidad');
        }
        if (!Schema::hasColumn('rayosx_orders', 'apellidos')) {
            $table->string('apellidos')->nullable()->after('nombres');
        }
        if (!Schema::hasColumn('rayosx_orders', 'datos_clinicos')) {
            $table->text('datos_clinicos')->nullable()->after('apellidos');
        }
    });
}

    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            if (Schema::hasColumn('rayosx_orders', 'datos_clinicos')) {
                $table->dropColumn('datos_clinicos');
            }
            if (Schema::hasColumn('rayosx_orders', 'apellidos')) {
                $table->dropColumn('apellidos');
            }
            if (Schema::hasColumn('rayosx_orders', 'nombres')) {
                $table->dropColumn('nombres');
            }
            if (Schema::hasColumn('rayosx_orders', 'identidad')) {
                $table->dropColumn('identidad');
            }
            if (Schema::hasColumn('rayosx_orders', 'edad')) {
                $table->dropColumn('edad');
            }
            // quitar fecha si la agregó esta migración
            if (Schema::hasColumn('rayosx_orders', 'fecha')) {
                // OJO: si la fecha ya era parte original y no quieres borrarla, elimina esta línea
                // $table->dropColumn('fecha');
            }
            if (Schema::hasColumn('rayosx_orders', 'paciente_id')) {
                // Si añadiste FK, deberías dropear la FK primero
                // $table->dropForeign(['paciente_id']);
                $table->dropColumn('paciente_id');
            }

            // Si el índice único fue borrado en up() no lo restauramos aquí (opcional)
        });
    }
};

