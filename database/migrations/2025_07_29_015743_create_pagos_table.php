<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('metodo_pago');
            $table->string('nombre_titular')->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->string('fecha_expiracion')->nullable();
            $table->string('cvv')->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
