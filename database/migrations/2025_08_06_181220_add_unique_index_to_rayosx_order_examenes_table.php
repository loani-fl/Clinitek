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
    Schema::table('rayosx_order_examenes', function (Blueprint $table) {
        $table->unique(['rayosx_order_id', 'examen']);
    });
}

public function down()
{
    Schema::table('rayosx_order_examenes', function (Blueprint $table) {
        $table->dropUnique(['rayosx_order_id', 'examen']);
    });
}

};
