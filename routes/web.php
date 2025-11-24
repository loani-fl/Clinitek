<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ConsultahoraController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\OrdenRayosXController;
use App\Http\Controllers\PacienteRayosXController;
use App\Http\Controllers\EmergenciaController;
use App\Http\Controllers\HospitalizacionController;
use App\Http\Controllers\SesionPsicologicaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ControlPrenatalController;
use App\Http\Controllers\UltrasonidoOrderController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recuperación de contraseña
Route::get('/password/forgot', [PasswordController::class, 'showForgot'])->name('password.forgot');
Route::post('/password/forgot', [PasswordController::class, 'reset'])->name('password.reset');

// Redirigir "/" al login
Route::get('/', function () {
    return redirect()->route('login.form');
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS POR SESIÓN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard / Inicio
    Route::get('/inicio', function () {
        return view('welcome');
    })->name('inicio');

    // Puestos
    Route::resource('puestos', PuestoController::class)
        ->middleware('role.permission:puestos.*');

    // Empleados
    Route::resource('empleado', EmpleadosController::class)
        ->middleware('role.permission:empleados.*');

    // Médicos
    Route::resource('medicos', MedicoController::class)->except(['destroy'])
        ->middleware('role.permission:medicos.*');
    Route::patch('/medicos/{medico}/estado', [MedicoController::class, 'toggleEstado'])
        ->name('medicos.toggleEstado')->middleware('role.permission:medicos.*');
    Route::get('/medicos/buscar', [MedicoController::class, 'buscar'])
        ->name('medicos.buscar')->middleware('role.permission:medicos.*');

    // Pacientes
    Route::resource('pacientes', PacienteController::class)
        ->middleware('role.permission:pacientes.*');
    Route::get('/pacientes/buscar', [PacienteController::class, 'buscar'])
        ->name('pacientes.buscar')->middleware('role.permission:pacientes.*');
    Route::get('/pacientes/datos/{id}', [PacienteController::class, 'obtenerDatos'])
        ->name('pacientes.datos')->middleware('role.permission:pacientes.*');

    // Consultas
    Route::resource('consultas', ConsultaController::class)
        ->middleware('role.permission:consultas.*');
    Route::get('/consultas/horas-ocupadas', [ConsultahoraController::class, 'horasOcupadas'])
        ->name('horas.ocupadas')->middleware('role.permission:consultas.*');

    // Diagnósticos
    Route::resource('diagnosticos', DiagnosticoController::class)
        ->middleware('role.permission:diagnosticos.*');

    // Exámenes
    Route::get('/examenes/create/{paciente}/{consulta}', [ExamenController::class, 'create'])
        ->name('examenes.create')->middleware('role.permission:examenes.*');
    Route::post('/examenes/store/{paciente}/{diagnostico}', [ExamenController::class, 'store'])
        ->name('examenes.store')->middleware('role.permission:examenes.*');
    Route::get('/examenes/{diagnostico}', [ExamenController::class, 'show'])
        ->name('examenes.show')->middleware('role.permission:examenes.*');

    // Rayos X
    Route::prefix('rayosx')->name('rayosx.')->group(function() {
        Route::get('/', [OrdenRayosXController::class, 'index'])->name('index')
            ->middleware('role.permission:rayosx.*');
        Route::get('/create', [OrdenRayosXController::class, 'create'])->name('create')
            ->middleware('role.permission:rayosx.*');
        Route::post('/store', [OrdenRayosXController::class, 'store'])->name('store')
            ->middleware('role.permission:rayosx.*');
        Route::get('/{orden}', [OrdenRayosXController::class, 'show'])->name('show')
            ->middleware('role.permission:rayosx.*');
        Route::patch('/{orden}/realizar', [OrdenRayosXController::class, 'marcarRealizado'])
            ->name('marcarRealizado')->middleware('role.permission:rayosx.*');
        Route::get('/{orden}/analisis', [OrdenRayosXController::class, 'analisis'])->name('analisis')
            ->middleware('role.permission:rayosx.*');
        Route::post('/{orden}/analisis', [OrdenRayosXController::class, 'storeAnalisis'])->name('storeAnalisis')
            ->middleware('role.permission:rayosx.*');
    });

    // Farmacias
    Route::resource('farmacias', FarmaciaController::class)
        ->middleware('role.permission:farmacias.*');

    // Facturas
    Route::get('/factura/{factura}', [FacturaController::class, 'show'])
        ->name('factura.show')->middleware('role.permission:facturas.*');

    // Emergencias
    Route::resource('emergencias', EmergenciaController::class)
        ->middleware('role.permission:emergencias.*');

    // Hospitalizaciones
    Route::resource('hospitalizaciones', HospitalizacionController::class)->except(['create'])
        ->middleware('role.permission:hospitalizaciones.*');
    Route::get('hospitalizaciones/create/{emergencia_id?}', [HospitalizacionController::class, 'create'])
        ->name('hospitalizaciones.create')->middleware('role.permission:hospitalizaciones.*');

    // Sesiones psicológicas
    Route::resource('sesiones', SesionPsicologicaController::class)
        ->middleware('role.permission:sesiones.*');

    // Inventario
    Route::resource('inventario', InventarioController::class)
        ->middleware('role.permission:inventario.*');

    // Control prenatal
    Route::resource('controles-prenatales', ControlPrenatalController::class)
        ->middleware('role.permission:controles_prenatales.*');

    // Ultrasonidos
    Route::prefix('ultrasonidos')->name('ultrasonidos.')->group(function() {
        Route::get('/', [UltrasonidoOrderController::class, 'index'])->name('index')
            ->middleware('role.permission:ultrasonidos.*');
        Route::get('/create', [UltrasonidoOrderController::class, 'create'])->name('create')
            ->middleware('role.permission:ultrasonidos.*');
        Route::post('/', [UltrasonidoOrderController::class, 'store'])->name('store')
            ->middleware('role.permission:ultrasonidos.*');
        Route::get('/{ultrasonido}', [UltrasonidoOrderController::class, 'show'])->name('show')
            ->middleware('role.permission:ultrasonidos.*');
        Route::get('/analisis/{id}', [UltrasonidoOrderController::class, 'analisis'])->name('analisis')
            ->middleware('role.permission:ultrasonidos.*');
        Route::post('/analisis/{id}', [UltrasonidoOrderController::class, 'storeAnalisis'])->name('storeAnalisis')
            ->middleware('role.permission:ultrasonidos.*');
    });

    // Rutas de administrador (usuarios y roles)
    Route::prefix('admin')->middleware('role.permission:administrador')->group(function () {
        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::get('usuarios/{id}/asignar', [UsuarioController::class, 'asignarVista'])->name('usuarios.asignar');
        Route::post('usuarios/{id}/asignar', [UsuarioController::class, 'asignarUpdate'])->name('usuarios.asignar.update');
    });

});
