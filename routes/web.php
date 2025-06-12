<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
;
use App\Http\Controllers\MedicoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí registras las rutas web de tu aplicación.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para médicos: usamos solo Route::resource para CRUD completo
Route::resource('medicos', MedicoController::class);
Route::patch('/medicos/{medico}/estado', [MedicoController::class, 'toggleEstado'])->name('medicos.toggleEstado');

