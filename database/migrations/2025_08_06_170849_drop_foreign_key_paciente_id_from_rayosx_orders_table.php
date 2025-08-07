<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
        });
    }

    public function down()
    {
        Schema::table('rayosx_orders', function (Blueprint $table) {
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('set null');
        });
    }
};

