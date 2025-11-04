<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUltrasonidoCrecimientoFetalsTable extends Migration
{
    public function up()
    {
        Schema::create('ultrasonido_crecimiento_fetal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ultrasonido_id')->constrained('ultrasonidos')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ultrasonido_crecimiento_fetal');
    }
}
