<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permisos
        $permisos = [
            'ver_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',
            'ver_roles',
            'crear_roles',
            'editar_roles',
            'eliminar_roles',
            'ver_pacientes',
            'ver_historial_medico',
            'ver_resultados_propios',
            'agendar_cita',
        ];

        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'administrador']);
        $medico = Role::firstOrCreate(['name' => 'medico']);
        $paciente = Role::firstOrCreate(['name' => 'paciente']);

        // Asignar permisos a roles
        $admin->syncPermissions($permisos); // Admin tiene todo
        $medico->syncPermissions(['ver_pacientes','ver_historial_medico']); // Médico permisos específicos
        $paciente->syncPermissions(['ver_resultados_propios','agendar_cita']); // Paciente permisos propios
    }
}
