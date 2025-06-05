<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Puesto;
use Faker\Factory as Faker;

class PuestoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $codigosUsados = [];

        foreach (range(1, 20) as $i) {
            // Generar código único
            do {
                $codigo = strtoupper($faker->bothify('PST-###??'));
            } while (in_array($codigo, $codigosUsados));

            $codigosUsados[] = $codigo;

            Puesto::create([
                'nombre'  => $faker->unique()->jobTitle,
                'codigo'  => $codigo, // ✅ único
                'sueldo'  => $faker->numberBetween(8000, 25000),
                'funcion' => $faker->sentence(6),
           
            ]);
        }
    }
}



