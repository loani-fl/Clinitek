<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToRayosxOrdersTable extends Migration
{
  

public function up()
{
    Schema::table('rayosx_orders', function (Blueprint $table) {
        if (!Schema::hasColumn('rayosx_orders', 'estado')) {
            $table->string('estado')->default('Pendiente')->after('apellidos');
        }
    });
}

    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
}
