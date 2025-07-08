<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ConsultahoraController;
use App\Models\Puesto;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome'); // Asumiendo que guardaste el archivo como welcome.blade.php
});
// Ruta para el inicio (home)
Route::get('/', function () {
    return view('welcome'); // O la vista que uses como inicio
})->name('inicio');




//Rutas para puestos
Route::get('/puestos/create', [PuestoController::class, 'create'])->name('puestos.create');
Route::post('/puestos', [PuestoController::class, 'store'])->name('puestos.store');


Route::get('/empleado', [EmpleadosController::class, 'index'])->name('empleado.index');
Route::get('/empleado/create', [EmpleadosController::class, 'create'])->name('empleado.create');
Route::post('/empleado', [EmpleadosController::class, 'store'])->name('empleado.store');
Route::get('/empleado/{empleado}', [EmpleadosController::class, 'show'])->name('empleado.show');
Route::get('/empleado/{empleado}/edit', [EmpleadosController::class, 'edit'])->name('empleado.edit');
Route::put('/empleado/{empleado}', [EmpleadosController::class, 'update'])->name('empleado.update');
Route::delete('/empleado/{empleado}', [EmpleadosController::class, 'destroy'])->name('empleado.destroy');

Route::resource('medicos', MedicoController::class)->except(['destroy']);
Route::patch('/medicos/{medico}/estado', [MedicoController::class, 'toggleEstado'])->name('medicos.toggleEstado');
Route::get('/medicos/buscar', [MedicoController::class, 'buscar'])->name('medicos.buscar');

// Solo deja esta línea para los puestos
Route::resource('puestos', PuestoController::class);
Route::get('/puestos/create', [PuestoController::class, 'create'])->name('puestos.create');

Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
Route::get('pacientes/{paciente}/edit', [App\Http\Controllers\PacienteController::class, 'edit'])->name('pacientes.edit');

Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');


Route::resource('consultas', ConsultaController::class);
Route::get('/consultas/horas-ocupadas', [ConsultaController::class, 'horasOcupadas']);
Route::get('/horas-ocupadas', [App\Http\Controllers\ConsultaController::class, 'horasOcupadas']);

Route::get('consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');
Route::put('consultas/{id}', [ConsultaController::class, 'update'])->name('consultas.update');
Route::get('/consultas/horas-ocupadas', [ConsultaController::class, 'horasOcupadas'])->name('consultas.horas.ocupadas');



Route::patch('/consultas/{consulta}/cancelar', [ConsultaController::class, 'cancelar'])->name('consultas.cancelar');



Route::get('/horas-ocupadas', [ConsultahoraController::class, 'horasOcupadas']);






