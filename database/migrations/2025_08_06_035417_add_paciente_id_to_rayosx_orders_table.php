<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->foreignId('paciente_id')
                  ->nullable()
                  ->constrained('pacientes')  // Cambia 'pacientes' si tu tabla tiene otro nombre
                  ->onDelete('set null')
                  ->after('diagnostico_id');  // Opcional: dónde quieres que quede la columna
        });
    }

    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);  // quitar la llave foránea
            $table->dropColumn('paciente_id');    // quitar la columna
        });
    }
};
