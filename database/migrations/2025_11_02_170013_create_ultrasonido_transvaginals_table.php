<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUltrasonidoTransvaginalsTable extends Migration
{
    public function up()
    {
        Schema::create('ultrasonido_transvaginal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ultrasonido_id')->constrained('ultrasonidos')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ultrasonido_transvaginal');
    }
}
