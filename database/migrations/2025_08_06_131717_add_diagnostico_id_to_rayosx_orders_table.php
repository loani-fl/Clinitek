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
        $table->unsignedBigInteger('diagnostico_id')->nullable()->after('id');
        // También puedes agregar llave foránea si quieres:
        // $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('rayosx_orders', function (Blueprint $table) {
        $table->dropColumn('diagnostico_id');
    });
}

};
