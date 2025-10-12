<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->string('categoria');
            $table->text('descripcion')->nullable();
            $table->integer('cantidad');
            $table->string('unidad')->nullable();
            $table->date('fecha_ingreso');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->string('proveedor')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};

