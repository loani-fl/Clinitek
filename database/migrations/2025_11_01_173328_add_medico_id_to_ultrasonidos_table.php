<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('ultrasonidos', function (Blueprint $table) {
        $table->unsignedBigInteger('medico_id')->nullable()->after('id');
        $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('ultrasonidos', function (Blueprint $table) {
        $table->dropForeign(['medico_id']);
        $table->dropColumn('medico_id');
    });
}

};
