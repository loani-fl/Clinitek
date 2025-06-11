<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\EmpleadosController;

Route::get('/', function () {
    return view('welcome');
});

// Puestos
Route::resource('puestos', PuestoController::class);
//Rutas para Registrar empleados
Route::get('/empleados', [EmpleadosController::class, 'create'])->name('empleados.create');
Route::post('/empleados', [EmpleadosController::class, 'store'])->name('empleados.store');
Route::get('puestos/{puesto}', [PuestoController::class, 'show'])->name('puestos.show');


