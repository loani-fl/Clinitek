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
    Schema::table('consultas', function (Blueprint $table) {
        $table->boolean('cancelada')->default(false)->after('sintomas');
    });
}

public function down()
{
    Schema::table('consultas', function (Blueprint $table) {
        $table->dropColumn('cancelada');
    });
}

};
