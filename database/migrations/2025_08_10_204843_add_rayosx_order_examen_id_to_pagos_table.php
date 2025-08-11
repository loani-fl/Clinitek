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
            $table->unsignedBigInteger('rayosx_order_examen_id')->nullable()->after('consulta_id');
            $table->foreign('rayosx_order_examen_id')->references('id')->on('rayosx_order_examenes')->onDelete('set null');
        });
        
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['rayosx_order_examen_id']);
            $table->dropColumn('rayosx_order_examen_id');
        });
    }
};
