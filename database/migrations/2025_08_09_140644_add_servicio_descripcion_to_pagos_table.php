<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

    
    class AddServicioDescripcionToPagosTable extends Migration
    {
        public function up()
        {
            Schema::table('pagos', function (Blueprint $table) {
                if (!Schema::hasColumn('pagos', 'servicio')) {
                    $table->string('servicio')->nullable()->after('metodo_pago');
                }
                if (!Schema::hasColumn('pagos', 'descripcion')) {
                    $table->string('descripcion')->nullable()->after('servicio');
                }
            });
        }
    
        public function down()
        {
            Schema::table('pagos', function (Blueprint $table) {
                if (Schema::hasColumn('pagos', 'servicio')) {
                    $table->dropColumn('servicio');
                }
                if (Schema::hasColumn('pagos', 'descripcion')) {
                    $table->dropColumn('descripcion');
                }
            });
        }
    }
    


