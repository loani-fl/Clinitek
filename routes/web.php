<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PuestoController;

// Ruta de inicio
Route::get('/', function () {
    return view('welcome');
});

// Rutas de empleados
Route::get('/empleado', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::get('/empleado/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('/empleado', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('/empleado/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');
Route::get('/empleado/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
Route::put('/empleado/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
Route::delete('/empleado/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

// Rutas de puestos
Route::resource('puestos', PuestoController::class);