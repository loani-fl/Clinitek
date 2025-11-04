<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUltrasonidoSonohisterografiasTableFinalV2 extends Migration


{
    public function up()
    {
        Schema::create('ultrasonido_sonohisterografia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ultrasonido_id')->constrained('ultrasonidos')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ultrasonido_sonohisterografia');
    }
}
