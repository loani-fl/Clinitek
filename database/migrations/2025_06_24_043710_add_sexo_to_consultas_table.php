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
            $table->string('genero', 10)->after('paciente_id'); // Ajusta la longitud si quieres
        });
    }
    
    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('genero');
        });
    }
};
