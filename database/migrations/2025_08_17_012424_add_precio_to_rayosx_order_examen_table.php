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
    Schema::table('rayosx_order_examen', function (Blueprint $table) {
        $table->decimal('precio', 8, 2)->nullable();
    });
}

public function down()
{
    Schema::table('rayosx_order_examen', function (Blueprint $table) {
        $table->dropColumn('precio');
    });
}

};
