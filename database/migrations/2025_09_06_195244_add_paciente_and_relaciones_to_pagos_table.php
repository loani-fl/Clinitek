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
    Schema::table('pagos', function (Blueprint $table) {
        $table->foreignId('paciente_id')->nullable()->constrained('pacientes')->onDelete('cascade');
        $table->string('origen')->nullable();       // ejemplo: rayos_x, laboratorio
        $table->unsignedBigInteger('referencia_id')->nullable(); // id de la orden
    });
}

public function down()
{
    Schema::table('pagos', function (Blueprint $table) {
        $table->dropForeign(['paciente_id']);
        $table->dropColumn(['paciente_id', 'origen', 'referencia_id']);
    });
}

};
