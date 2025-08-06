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
    Schema::table('rayosx_orders', function (Blueprint $table) {
        if (!Schema::hasColumn('rayosx_orders', 'paciente_tipo')) {
            $table->string('paciente_tipo')->default('clinica')->after('paciente_id');
        }
    });
}

public function down()
{
    Schema::table('rayosx_orders', function (Blueprint $table) {
        $table->dropColumn('paciente_tipo');
    });
}

};
