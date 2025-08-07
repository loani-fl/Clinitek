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
        $table->unsignedBigInteger('diagnostico_id')->nullable()->unique()->change();
    });
}

public function down()
{
    Schema::table('rayosx_orders', function (Blueprint $table) {
        $table->unsignedBigInteger('diagnostico_id')->unique()->nullable(false)->change();
    });
}

};
