<?php

// database/migrations/xxxx_xx_xx_create_rayosx_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rayosx_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostico_id')->unique()->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->text('datos_clinicos')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rayosx_orders');
    }
};
