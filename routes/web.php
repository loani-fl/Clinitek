<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\MedicoController;


// Ruta para mostrar el formulario
Route::get('/medicos/crear', [MedicoController::class, 'create'])->name('medicos.create');

// Ruta para guardar los datos del médico
Route::post('/medicos', [MedicoController::class, 'store'])->name('medicos.store');


Route::get('/medicos', [MedicoController::class, 'index'])->name('medicos.index');

Route::get('/medicos/ver', [MedicoController::class, 'show'])->name('medicos.show');

// Mostrar el formulario de edición
Route::get('/medicos/{id}/edit', [MedicoController::class, 'edit'])->name('medicos.edit');

// Actualizar el médico
Route::put('/medicos/{id}', [MedicoController::class, 'update'])->name('medicos.update');



Route::resource('medicos', MedicoController::class);
