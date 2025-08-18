<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->foreignId('consulta_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropForeign(['consulta_id']);
            $table->dropColumn('consulta_id');
        });
    }
};
