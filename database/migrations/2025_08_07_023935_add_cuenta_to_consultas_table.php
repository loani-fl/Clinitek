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
            $table->string('cuenta')->nullable()->after('id'); // O ubica después de la columna que prefieras
        });
    }
    
    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('cuenta');
        });
    }
    
};
