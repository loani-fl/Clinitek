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
        Schema::table('recetas', function (Blueprint $table) {
            $table->unsignedBigInteger('paciente_id')->nullable()->after('consulta_id');
    
            $table->foreign('paciente_id')
                  ->references('id')
                  ->on('pacientes')
                  ->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropColumn('paciente_id');
        });
    }
    
};
