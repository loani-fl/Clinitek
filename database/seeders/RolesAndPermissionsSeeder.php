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

        /* =============================================================
         *  PERMISOS YA EXISTENTES EN TU SISTEMA
         * ============================================================= */
        $permisosBase = [


            //      Roles
            'ver_roles',
            'crear_roles',
            'editar_roles',
            'eliminar_roles',


        ];


        // -------------------------
        //      CONSULTAS
        // -------------------------
        $consultas = [
            'consultas.index',
            'consultas.create',
            'consultas.edit',
            'consultas.show',
        ];

        // -------------------------
        //   CONTROL PRENATAL
        // -------------------------
        $controlPrenatal = [
            'controlPrenatal.index',
            'controlPrenatal.create',
            'controlPrenatal.show',
        ];

        // -------------------------
        //       DASHBOARD
        // -------------------------
        $dashboard = [
            'dashboard.inicio',
        ];

        // -------------------------
        //    DIAGNÓSTICOS
        // -------------------------
        $diagnosticos = [
            'diagnosticos.create',
            'diagnosticos.edit',
            'diagnosticos.show',
        ];

        // -------------------------
        //     EMERGENCIAS
        // -------------------------
        $emergencias = [
            'emergencias.index',
            'emergencias.create',
            'emergencias.show',
        ];

        // --- EMPLEADO ---
        $empleado = [
            'empleado.index',
            'empleado.create',
            'empleado.edit',
            'empleado.show',
        ];

        // --- EXÁMENES ---
        $examenes = [
            'examenes.create',
            'examenes.show',
        ];

        // --- FACTURA ---
        $factura = [
            'factura.show',
        ];

        // --- FARMACIAS ---
        $farmacias = [
            'farmacias.index',
            'farmacias.create',
            'farmacias.edit',
            'farmacias.show',
        ];

        // --- HOSPITALIZACIÓN ---
        $hospitalizacion = [
            'hospitalizacion.create',
            'hospitalizacion.imprimir',
        ];

            // INVENTARIO
            $inventario = [
                'inventario.index',
                'inventario.create',
                'inventario.edit',
                'inventario.show',
            ];

        // MÉDICOS
        $medicos = [
            'medicos.index',
            'medicos.create',
            'medicos.edit',
            'medicos.show',
        ];

        // PACIENTES
        $pacientes = [
            'pacientes.index',
            'pacientes.create',
            'pacientes.edit',
            'pacientes.show',
        ];

        // --- PUESTOS ---
        $puestos = [
            'puestos.index',
            'puestos.create',
            'puestos.edit',
            'puestos.show',
        ];

// --- RAYOS X ---
        $rayosX = [
            'rayosX.analisis',
            'rayosX.create',
            'rayosX.index',
            'rayosX.show',
        ];

// --- RECETAS ---
        $recetas = [
            'recetas.create',
            'recetas.no-disponible',
            'recetas.show',
        ];

// --- SESIONES ---
        $sesiones = [
            'sesiones.index',
            'sesiones.create',
            'sesiones.show',
        ];
        // --- ULTRASONIDOS ---
        $ultrasonidos = [
            'ultrasonidos.analisis',
            'ultrasonidos.create',
            'ultrasonidos.index',
            'ultrasonidos.show',
        ];

// --- USUARIOS ---
        $usuarios = [
            'usuarios.asignar',
            'usuarios.create',
            'usuarios.index',
            'usuarios.edit',
            'usuarios.show',
            'usuarios.eliminar',
        ];


        /* =============================================================
         *  UNIR TODO PARA CREAR PERMISOS AUTOMÁTICAMENTE
         * ============================================================= */
        $todosLosPermisos = array_merge(
            $permisosBase,
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

        foreach ($todosLosPermisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        /* =============================================================
         *  ROLES
         * ============================================================= */
        $admin = Role::firstOrCreate(['name' => 'administrador']);
        $medico = Role::firstOrCreate(['name' => 'medico']);
        $paciente = Role::firstOrCreate(['name' => 'paciente']);
        $empleado = Role::firstOrCreate(['name' => 'empleado']);

        /* =============================================================
         *  ASIGNACIÓN DE PERMISOS A ROLES
         * ============================================================= */

        // ADMIN — TODO
        $admin->syncPermissions($todosLosPermisos);

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

        // PACIENTE
        $paciente->syncPermissions([
            'consular.show',
            'consultas.create',
            'pacientes.show',
            'pacientes.create',
            'pacientes.edit',
        ]);

        $empleado->syncPermissions([

            'empleado.show',
            'empleado.create',
            'empleado.edit',
        ]);
    }
}
