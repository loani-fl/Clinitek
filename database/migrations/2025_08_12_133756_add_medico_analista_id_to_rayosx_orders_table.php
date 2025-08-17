<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedicoAnalistaIdToRayosxOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            // Solo agregar si la columna no existe
            if (!Schema::hasColumn('rayosx_orders', 'medico_analista_id')) {
                $table->unsignedBigInteger('medico_analista_id')->nullable()->after('paciente_id');
                $table->foreign('medico_analista_id')
                      ->references('id')
                      ->on('medicos')
                      ->onDelete('set null');
            }
        });
    }
    
    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropForeign(['medico_analista_id']);
            $table->dropColumn('medico_analista_id');
        });
    }
}

