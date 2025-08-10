<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    if (!Schema::hasTable('rayosx_order_examenes')) {
        Schema::create('rayosx_order_examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rayosx_order_id')->constrained('rayosx_orders')->onDelete('cascade');
            $table->string('examen_codigo'); // ejemplo: 'torax_pa'
            $table->text('descripcion')->nullable();
            $table->string('imagen_path')->nullable();
            $table->timestamps();

            $table->unique(['rayosx_order_id', 'examen_codigo']);
        });
    }
}


    public function down()
    {
        Schema::dropIfExists('rayosx_order_examenes');
    }
};
