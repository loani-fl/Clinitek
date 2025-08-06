<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsultaIdToPagosTable extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('consulta_id')->nullable()->after('id'); // o después del campo que quieras
            // Si quieres establecer clave foránea:
            $table->foreign('consulta_id')->references('id')->on('consultas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['consulta_id']);
            $table->dropColumn('consulta_id');
        });
    }
}
