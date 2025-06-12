<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\EmpleadoController;

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

// Solo esta ruta protegida por sesión
Route::middleware('check.sesion')->get('/empleados/visualizacion', [EmpleadoController::class, 'visualizacion'])->name('empleados.visualizacion');

// Rutas públicas de empleados
Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');
Route::get('/empleados/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

// Rutas públicas de puestos (incluye show)
Route::get('/puestos', [PuestoController::class, 'index'])->name('puestos.index');
Route::get('/puestos/create', [PuestoController::class, 'create'])->name('puestos.create');
Route::post('/puestos', [PuestoController::class, 'store'])->name('puestos.store');
Route::get('/puestos/{puesto}', [PuestoController::class, 'show'])->name('puestos.show');
Route::get('/puestos/{puesto}/edit', [PuestoController::class, 'edit'])->name('puestos.edit');
Route::put('/puestos/{puesto}', [PuestoController::class, 'update'])->name('puestos.update');
Route::delete('/puestos/{puesto}', [PuestoController::class, 'destroy'])->name('puestos.destroy');
