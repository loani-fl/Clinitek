<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Si tu base usa esos nombres por defecto, intentaremos eliminarlos; si no, no romperá.
        // Revisa SHOW CREATE TABLE rayosx_orders si falla.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Dropear índice unique si existe
        try { DB::statement('ALTER TABLE `rayosx_orders` DROP INDEX `rayosx_orders_diagnostico_id_unique`;'); } catch (\Exception $e) {}

        // Dropear FK si existe
        try { DB::statement('ALTER TABLE `rayosx_orders` DROP FOREIGN KEY `rayosx_orders_diagnostico_id_foreign`;'); } catch (\Exception $e) {}

        // Modificar la columna para permitir NULL
        // Ajusta el tipo si tu columna es diferente
        DB::statement('ALTER TABLE `rayosx_orders` MODIFY `diagnostico_id` BIGINT UNSIGNED NULL;');

        // Volver a crear FK (opcional) sin unique
        try {
            DB::statement('ALTER TABLE `rayosx_orders` ADD CONSTRAINT `rayosx_orders_diagnostico_id_foreign` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnosticos`(`id`) ON DELETE CASCADE;');
        } catch (\Exception $e) {}

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // revertir: volver a not null (si quieres)
        try {
            DB::statement('ALTER TABLE `rayosx_orders` DROP FOREIGN KEY `rayosx_orders_diagnostico_id_foreign`;');
        } catch (\Exception $e) {}
        DB::statement('ALTER TABLE `rayosx_orders` MODIFY `diagnostico_id` BIGINT UNSIGNED NOT NULL;');

        // volver a crear unique si lo quieres (cuidado)
        try { DB::statement('CREATE UNIQUE INDEX `rayosx_orders_diagnostico_id_unique` ON `rayosx_orders`(`diagnostico_id`);'); } catch (\Exception $e) {}

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
