<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MedicoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para médicos (sin destroy)
Route::resource('medicos', MedicoController::class)->except(['destroy']);

// Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    if ($request->input('clave') === '1234') {
        session([
            'autenticado' => true,
            'usuario_nombre' => 'Administrador',
            'permisos' => ['ver_empleados', 'crear_empleados', 'editar_empleados', 'eliminar_empleados']
        ]);
        return redirect()->route('empleados.visualizacion');
    }

    return back()->withErrors(['clave' => 'Clave incorrecta.']);
})->name('login.submit');

Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

// Ruta protegida para visualización de empleados
Route::middleware('check.sesion')->get('/empleados/visualizacion', [EmpleadoController::class, 'visualizacion'])->name('empleados.visualizacion');

// Rutas para empleados y puestos
Route::resource('empleados', EmpleadoController::class);
Route::resource('puestos', PuestoController::class);
