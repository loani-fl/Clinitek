<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicioAndDescripcionToPagosTable extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('servicio')->after('metodo_pago');
            $table->string('descripcion_servicio')->nullable()->after('servicio');
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('servicio');
            $table->dropColumn('descripcion_servicio');
        });
    }
}
