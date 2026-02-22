<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o recuperar usuario administrador
        $admin = Usuario::firstOrCreate(
            ['email' => 'admin@clinitek.com'],
            [
                'name' => 'Maria Zavala',
                'password' => Hash::make('NuevaPass123'),
                // ❌ NO poner 'rol' aquí
            ]
        );

        // Obtener rol administrador
        $roleAdmin = Role::where('name', 'administrador')->first();

        // Asignar rol si no lo tiene
        if ($roleAdmin && !$admin->hasRole('administrador')) {
            $admin->assignRole($roleAdmin);
        }
    }
}
