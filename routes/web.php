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
use App\Models\Puesto;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\MedicamentoController;

Route::get('/medicamentos/search', [MedicamentoController::class, 'search'])->name('medicamentos.search');

// Página de bienvenida
Route::get('/', function () {
    return view('welcome'); // Asumiendo que guardaste el archivo como welcome.blade.php
});
// Ruta para el inicio (home)
Route::get('/', function () {
    return view('welcome'); // O la vista que uses como inicio
})->name('inicio');

// Rutas para puestos
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
Route::get('/horas-ocupadas', [ConsultaController::class, 'horasOcupadas']);


Route::get('/horas-ocupadas', [ConsultahoraController::class, 'horasOcupadas'])->name('horas.ocupadas');

Route::get('consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');
Route::put('consultas/{id}', [ConsultaController::class, 'update'])->name('consultas.update');
Route::get('/consultas/horas-ocupadas', [ConsultaController::class, 'horasOcupadas'])->name('consultas.horas.ocupadas');
Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');
Route::patch('/consultas/{consulta}/cancelar', [ConsultaController::class, 'cancelar'])->name('consultas.cancelar');
Route::patch('/consultas/{consulta}/cambiar-estado', [ConsultaController::class, 'cambiarEstado'])->name('consultas.cambiarEstado');

Route::resource('diagnosticos', DiagnosticoController::class);

Route::get('/recetas/create/{consulta}', [RecetaController::class, 'create'])->name('recetas.create');
Route::post('/recetas/{consulta}', [RecetaController::class, 'store'])->name('recetas.store');
Route::post('recetas/{paciente}', [RecetaController::class, 'store'])->name('recetas.store');

Route::get('/pacientes/{paciente}/recetas', [PacienteController::class, 'showRecetas'])->name('recetas.show');
Route::get('/consultas/{id}', [ConsultaController::class, 'show'])->name('consultas.show');

// Ruta para mostrar formulario con paciente y consulta
Route::get('diagnosticos/create/{paciente}/{consulta}', [DiagnosticoController::class, 'create'])->name('diagnosticos.create');

// Ruta para guardar diagnóstico
Route::post('diagnosticos', [DiagnosticoController::class, 'store'])->name('diagnosticos.store');
Route::get('diagnosticos/{diagnostico}/edit', [DiagnosticoController::class, 'edit'])->name('diagnosticos.edit');
Route::put('diagnosticos/{diagnostico}', [DiagnosticoController::class, 'update'])->name('diagnosticos.update');

// Ruta para mostrar detalle del diagnóstico
Route::get('diagnosticos/{diagnostico}', [DiagnosticoController::class, 'show'])->name('diagnosticos.show');

// Rutas para ExamenController
Route::get('/pacientes/{paciente}/consultas/{consulta}/examenes/create', [ExamenController::class, 'create'])->name('examenes.create');
Route::post('/pacientes/{paciente}/consultas/{consulta}/examenes', [ExamenController::class, 'store'])->name('examenes.store');

// prueba show
Route::get('/examenes/prueba/{consulta}', [ExamenController::class, 'showPrueba'])->name('examenes.show');
Route::get('/examenes/create/{paciente_id}/{consulta_id}', [ExamenController::class, 'create'])->name('examenes.create');
Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');

// ejemplo de rutas en web.php para show
Route::get('examenes/{paciente}/{consulta}', [ExamenController::class, 'show'])->name('examenes.show');
Route::post('examenes/{paciente}/{consulta}', [ExamenController::class, 'store'])->name('examenes.store');


Route::post('/consultas/{id}/cambiar-estado', [ConsultaController::class, 'cambiarEstado'])->name('consultas.cambiarEstado');




