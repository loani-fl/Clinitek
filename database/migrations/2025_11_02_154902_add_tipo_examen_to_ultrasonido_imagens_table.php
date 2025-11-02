<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_tipo_examen_to_ultrasonido_imagens_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ultrasonido_imagens', function (Blueprint $table) {
            // Columna que guardará la clave del examen (ej: 'higado', 'vesicula')
            $table->string('tipo_examen')->nullable()->after('ultrasonido_id');
        });
    }

    public function down(): void
    {
        Schema::table('ultrasonido_imagens', function (Blueprint $table) {
            $table->dropColumn('tipo_examen');
        });
    }
};
