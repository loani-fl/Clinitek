<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->string('genero')->nullable()->after('paciente_id');
        });
    }
    
    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('genero');
        });
    }
    
};
