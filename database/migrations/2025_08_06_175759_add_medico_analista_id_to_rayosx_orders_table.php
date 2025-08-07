<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('rayosx_orders', 'medico_analista_id')) {
                $table->foreignId('medico_analista_id')
                      ->nullable()
                      ->constrained('medicos')
                      ->onDelete('set null')
                      ->after('medico_radiologo_id'); // o after('paciente_id') según prefieras
            }
        });
    }

    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            if (Schema::hasColumn('rayosx_orders', 'medico_analista_id')) {
                $table->dropForeign(['medico_analista_id']);
                $table->dropColumn('medico_analista_id');
            }
        });
    }
};
