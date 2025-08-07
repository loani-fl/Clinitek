<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTableUnificada extends Migration

{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->string('metodo_pago'); // Ejemplo: tarjeta, efectivo

            $table->string('servicio')->nullable();

            $table->string('descripcion')->nullable();

            $table->string('nombre_titular')->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->string('fecha_expiracion')->nullable();
            $table->string('cvv')->nullable();

            $table->decimal('cantidad', 10, 2);

            $table->date('fecha');

            $table->unsignedBigInteger('consulta_id')->nullable();
            $table->foreign('consulta_id')->references('id')->on('consultas')->onDelete('set null');

            $table->string('estado_pago', 20)->default('pendiente');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
