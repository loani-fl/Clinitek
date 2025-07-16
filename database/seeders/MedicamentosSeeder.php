<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicamento;

class MedicamentosSeeder extends Seeder
{
    public function run(): void
    {
        $medicamentos = [
            'Panadol Ultra',
            'Panadol Extra',
            'Panadol Cold & Flu',
            'Panadol Actifast',
            'Panadol Infant Suspension',

            // Paracetamol genéricos y otros nombres comerciales
            'Paracetamol Infantil',
            'Paracetamol Gotas',
            'Tylenol Infantil',
            'Tempra Infantil',
            'Dolo Infantil',
            'Acetaminofén Suspensión',

            // Otros ejemplos comunes
            'Clonazepam',
            'Ibuprofeno',
            'Amoxicilina',
            'Loratadina',
            'Omeprazol',
        ];

        foreach ($medicamentos as $nombre) {
            Medicamento::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
