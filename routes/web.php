<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;

// Página de bienvenida / Inicio
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

// Rutas para puestos
Route::resource('puestos', PuestoController::class);
Route::get('/puestos/create', [PuestoController::class, 'create'])->name('puestos.create');

// Rutas para empleados
Route::get('/empleado', [EmpleadosController::class, 'index'])->name('empleado.index');
Route::get('/empleado/create', [EmpleadosController::class, 'create'])->name('empleado.create');
Route::post('/empleado', [EmpleadosController::class, 'store'])->name('empleado.store');
Route::get('/empleado/{empleado}', [EmpleadosController::class, 'show'])->name('empleado.show');
Route::get('/empleado/{empleado}/edit', [EmpleadosController::class, 'edit'])->name('empleado.edit');
Route::put('/empleado/{empleado}', [EmpleadosController::class, 'update'])->name('empleado.update');
Route::delete('/empleado/{empleado}', [EmpleadosController::class, 'destroy'])->name('empleado.destroy');

// Rutas para médicos
Route::resource('medicos', MedicoController::class)->except(['destroy']);
Route::patch('/medicos/{medico}/estado', [MedicoController::class, 'toggleEstado'])->name('medicos.toggleEstado');
Route::get('/medicos/buscar', [MedicoController::class, 'buscar'])->name('medicos.buscar');

// Rutas para pacientes
Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
Route::get('pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');

// Rutas para consultas
Route::resource('consultas', ConsultaController::class);
Route::get('/consultas/horas-ocupadas', [ConsultaController::class, 'horasOcupadas'])->name('consultas.horas.ocupadas');
Route::patch('/consultas/{consulta}/cancelar', [ConsultaController::class, 'cancelar'])->name('consultas.cancelar');
Route::get('/consultas/buscar', [ConsultaController::class, 'buscarAjax'])->name('consultas.buscarAjax'); // <-- Nueva ruta para búsqueda AJAX

// Rutas para editar y actualizar consultas (por si no están en resource)
Route::get('consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');
Route::put('consultas/{id}', [ConsultaController::class, 'update'])->name('consultas.update');
