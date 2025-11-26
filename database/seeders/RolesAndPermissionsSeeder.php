<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------
        // PERMISOS
        // -------------------------

        // Roles
        $roles = ['ver roles', 'crear roles', 'editar roles', 'eliminar roles'];

        // Consultas
        $consultas = ['ver consultas', 'crear consultas', 'editar consultas', 'ver consultas'];

        // Control prenatal
        $controlPrenatal = ['ver controles prenatales', 'crear controles prenatales', 'editar controles prenatales'];

        // Dashboard
        $dashboard = ['dashboard inicio'];

        // Diagnósticos
        $diagnosticos = ['ver diagnosticos', 'crear diagnosticos', 'editar diagnosticos'];

        // Emergencias
        $emergencias = ['ver emergencias', 'crear emergencias', 'editar emergencias'];

        // Empleados
        $empleado = ['ver empleados', 'crear empleados', 'editar empleados'];

        // Exámenes
        $examenes = ['ver examenes', 'crear examenes', 'editar examenes'];

        // Factura
        $factura = ['ver facturas'];

        // Farmacias
        $farmacias = ['ver farmacias', 'crear farmacias', 'editar farmacias'];

        // Hospitalización
        $hospitalizacion = ['ver hospitalizaciones', 'crear hospitalizaciones', 'imprimir hospitalizaciones'];

        // Inventario
        $inventario = ['ver inventario', 'crear inventario', 'editar inventario'];

        // Médicos
        $medicos = ['ver medicos', 'crear medicos', 'editar medicos'];

        // Pacientes
        $pacientes = ['ver pacientes', 'crear pacientes', 'editar pacientes'];

        // Puestos
        $puestos = ['ver puestos', 'crear puestos', 'editar puestos'];

        // Rayos X
        $rayosX = ['ver rayosx', 'crear rayosx', 'editar rayosx', 'analisis rayosx'];

        // Recetas
        $recetas = ['ver recetas', 'crear recetas', 'editar recetas'];

        // Sesiones
        $sesiones = ['ver sesiones', 'crear sesiones', 'editar sesiones'];

        // Ultrasonidos
        $ultrasonidos = ['ver ultrasonidos', 'crear ultrasonidos', 'editar ultrasonidos', 'analisis ultrasonidos'];

        // Usuarios
        $usuarios = ['ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'asignar usuarios'];


        // -------------------------
        // UNIR TODOS LOS PERMISOS
        // -------------------------
        $todosLosPermisos = array_merge(
            $roles,
            $consultas,
            $controlPrenatal,
            $dashboard,
            $diagnosticos,
            $emergencias,
            $empleado,
            $examenes,
            $factura,
            $farmacias,
            $hospitalizacion,
            $inventario,
            $medicos,
            $pacientes,
            $puestos,
            $rayosX,
            $recetas,
            $sesiones,
            $ultrasonidos,
            $usuarios
        );

        // Crear permisos
        foreach ($todosLosPermisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // -------------------------
        // ROLES
        // -------------------------
        $admin = Role::firstOrCreate(['name' => 'administrador']);
        $admin->syncPermissions($todosLosPermisos);

<<<<<<< Updated upstream
        // Rol de recepción, solo acceso a pacientes
=======
        // MÉDICO
        $medico->syncPermissions([

            // Consultas
            'consultas.index',
            'consultas.create',
            'consultas.edit',
            'consultas.show',

            // Control Prenatal
            'controlPrenatal.index',
            'controlPrenatal.create',
            'controlPrenatal.show',

            // Diagnósticos
            'diagnosticos.create',
            'diagnosticos.edit',
            'diagnosticos.show',

            // Emergencias
            'emergencias.index',
            'emergencias.create',
            'emergencias.show',

            // Exámenes
            'examenes.create',
            'examenes.show',

            // Hospitalización
            'hospitalizacion.create',
            'hospitalizacion.imprimir',

            // Dashboard
            'dashboard.inicio',

            // Médicos
            'medicos.index',
            'medicos.show',
             'medicos.create',

            // Pacientes
            'pacientes.index',
            'pacientes.show',
        ]);

        $empleado->syncPermissions([

            'empleado.show',
            'empleado.create',
            'empleado.edit',

        ]);

// ID del usuario administrador
        $usuario = Usuario::first();

            if ($usuario) {
                $usuario->assignRole('administrador');
            }
>>>>>>> Stashed changes

    }
}

