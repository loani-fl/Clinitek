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
            if (!Schema::hasColumn('rayosx_order_examenes', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('examen_codigo');
            }
        });
    }
    

public function down()
{
    Schema::table('rayosx_order_examenes', function (Blueprint $table) {
        $table->dropColumn('descripcion');
    });
}

};
