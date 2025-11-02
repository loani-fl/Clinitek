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
use App\Models\Puesto;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\PagoController;

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

//BUSCAR EN EMERGENCIAS
Route::get('/pacientes/buscar', [PacienteController::class, 'buscarPaciente'])->name('pacientes.buscar');
// Ruta para obtener datos completos del paciente
Route::get('/pacientes/datos/{id}', [PacienteController::class, 'obtenerDatosPacienteCompleto'])->name('pacientes.datosCompleto');

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

//posible ruta a eliminar
Route::get('consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');


Route::put('consultas/{id}', [ConsultaController::class, 'update'])->name('consultas.update');
Route::get('/consultas/horas-ocupadas', [ConsultaController::class, 'horasOcupadas'])->name('consultas.horas.ocupadas');
Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');
Route::patch('/consultas/{consulta}/cancelar', [ConsultaController::class, 'cancelar'])->name('consultas.cancelar');
Route::patch('/consultas/{consulta}/cambiar-estado', [ConsultaController::class, 'cambiarEstado'])->name('consultas.cambiarEstado');
//Route::get('/pago/create', [PagoController::class, 'create'])->name('pago.create');


Route::resource('diagnosticos', DiagnosticoController::class);

Route::get('/recetas/create/{consulta}', [RecetaController::class, 'create'])->name('recetas.create');
Route::post('/recetas/{consulta}', [RecetaController::class, 'store'])->name('recetas.store');
Route::get('/recetas/{id}', [RecetaController::class, 'show'])->name('recetas.show');

Route::get('/pacientes/{paciente}/recetas', [PacienteController::class, 'showRecetas'])->name('pacientes.recetas');
Route::get('/consultas/{id}', [ConsultaController::class, 'show'])->name('consultas.show');
Route::resource('consultas', ConsultaController::class);

// Ruta para mostrar formulario con paciente y consulta
Route::get('diagnosticos/create/{paciente}/{consulta}', [DiagnosticoController::class, 'create'])->name('diagnosticos.create');

// Ruta para guardar diagnóstico
Route::post('diagnosticos', [DiagnosticoController::class, 'store'])->name('diagnosticos.store');
Route::get('diagnosticos/{diagnostico}/edit', [DiagnosticoController::class, 'edit'])->name('diagnosticos.edit');
Route::put('diagnosticos/{diagnostico}', [DiagnosticoController::class, 'update'])->name('diagnosticos.update');

// Ruta para mostrar detalle del diagnóstico
Route::get('diagnosticos/{diagnostico}', [DiagnosticoController::class, 'show'])->name('diagnosticos.show');

// Rutas para Estado de consultas

Route::post('/consultas/{id}/cambiar-estado', [ConsultaController::class, 'cambiarEstado'])->name('consultas.cambiarEstado');
Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');


// Rutas para ExamenController
Route::get('/examenes/create/{paciente}/{consulta}', [ExamenController::class, 'create'])->name('examenes.create');

Route::post('/examenes/store/{paciente}/{diagnostico}', [ExamenController::class, 'store'])->name('examenes.store');
//Route::get('/examenes/{paciente}/{consulta}', [ExamenController::class, 'show'])->name('examenes.show');


Route::get('/examenes/{diagnostico}', [ExamenController::class, 'show'])->name('examenes.show');
Route::get('/ordenes/{id}', [ExamenController::class, 'detalleOrden'])->name('ordenes.detalle');

Route::get('/ordenes/{id}', [ExamenController::class, 'detalleOrden'])->name('ordenes.detalle');

// RAYOS X
use App\Http\Controllers\OrdenRayosXController;

Route::prefix('rayosx')->group(function () {
    Route::get('/', [OrdenRayosXController::class, 'index'])->name('rayosx.index');
    Route::get('/create', [OrdenRayosXController::class, 'create'])->name('rayosx.create');
    Route::post('/store', [OrdenRayosXController::class, 'store'])->name('rayosx.store');
    Route::get('/{orden}', [OrdenRayosXController::class, 'show'])->name('rayosx.show');

   Route::patch('/{orden}/realizar', [OrdenRayosXController::class, 'marcarRealizado'])->name('rayosx.marcarRealizado');
});
// Rutas para pago
// Desde consulta → crea el pago y redirige a show
Route::get('/pago/consulta/{consulta_id}', [PagoController::class, 'createFromConsulta'])->name('pagos.consulta');
// Desde rayos X → crea el pago y redirige a show
Route::get('/pago/rayosx/{rayosx_id}', [PagoController::class, 'createFromRayosx'])->name('pagos.rayosx');
// Mostrar factura
Route::get('/pago/{id}', [PagoController::class, 'show'])->name('pagos.show');
// Guardar pago
Route::post('/pago/store', [PagoController::class, 'store'])->name('pagos.store');

//NUEVO

// Mostrar formulario para crear paciente solo para Rayos X
Route::get('/pacientes/rayosx/create', [App\Http\Controllers\PacienteRayosXController::class, 'create'])
    ->name('pacientes.rayosx.create');

// Guardar paciente nuevo para Rayos X
Route::post('/pacientes/rayosx/store', [App\Http\Controllers\PacienteRayosXController::class, 'store'])
    ->name('pacientes.rayosx.store');


//validar identidad

use App\Http\Controllers\PacienteRayosXController;

Route::get('/pacientes/validar-identidad/{identidad}', [PacienteRayosXController::class, 'validarIdentidad'])->name('pacientes.validarIdentidad');
Route::get('pacientes/rayosx/create', [PacienteRayosXController::class, 'create'])->name('pacientes.rayosx.create');
Route::post('pacientes/rayosx/store', [PacienteRayosXController::class, 'store'])->name('pacientes.rayosx.store');


Route::get('rayosx/{id}', [OrdenRayosXController::class, 'show'])->name('rayosx.show');

// ORDEN
Route::post('/rayosx/descripcion-guardar', [OrdenRayosXController::class, 'guardarDescripcion'])
    ->name('rayosx.descripcion.guardar');


    //OOTRAS
    Route::get('/rayosx', [OrdenRayosXController::class, 'index'])->name('rayosx.index');
Route::get('/rayosx/create', [OrdenRayosXController::class, 'create'])->name('rayosx.create');
Route::post('/rayosx', [OrdenRayosXController::class, 'store'])->name('rayosx.store');
Route::get('/rayosx/{id}', [OrdenRayosXController::class, 'show'])->name('rayosx.show');
Route::get('/rayosx/{id}/edit', [OrdenRayosXController::class, 'edit'])->name('rayosx.edit');
Route::put('/rayosx/{id}', [OrdenRayosXController::class, 'update'])->name('rayosx.update');
Route::delete('/rayosx/{id}', [OrdenRayosXController::class, 'destroy'])->name('rayosx.destroy');


Route::post('/pacientes/rayosx/store', [App\Http\Controllers\PacienteRayosXController::class, 'storePacienteRayosX'])->name('pacientes.rayosx.store');
Route::post('pacientes/rayosx/store', [PacienteRayosXController::class, 'store'])->name('pacientes.rayosx.store');



Route::post('/rayosx/descripcion/guardar', [OrdenRayosXController::class, 'guardarDescripcion'])->name('rayosx.descripcion.guardar');

//ANALISIS ;

Route::get('rayosx/{orden}/analisis', [OrdenRayosXController::class, 'analisis'])->name('rayosx.analisis');
Route::post('rayosx/{orden}/analisis', [OrdenRayosXController::class, 'guardarAnalisis'])->name('rayosx.analisis.guardar');
Route::put('/rayosx/{orden}/guardar-analisis', [OrdenRayosXController::class, 'guardarAnalisis'])->name('rayosx.guardarAnalisis');



//AQUI ABJAO
Route::get('/rayosx/{id}/analizar', [OrdenRayosXController::class, 'analizar'])->name('rayosx.analizar');



Route::get('/pacientes/buscar-ajax', [PacienteController::class, 'buscarPacientesAjax'])->name('pacientes.buscarAjax');



Route::get('/pacientes/{id}/datos', [PacienteController::class, 'datos'])->name('pacientes.datos');
Route::get('/pacientes/{id}/datos', [PacienteController::class, 'obtenerDatosPaciente'])->name('pacientes.datos');
Route::get('rayosx', [OrdenRayosXController::class, 'index'])->name('rayosx.index');
Route::get('rayosx/{orden}/analisis', [OrdenRayosXController::class, 'analisis'])->name('rayosx.analisis');

Route::get('/rayosx/{orden}/analisis', [OrdenRayosXController::class, 'analisis'])->name('rayosx.analisis');



Route::get('rayosx/{id}/analisis', [OrdenRayosXController::class, 'analisis'])->name('rayosx.analisis');
Route::post('rayosx/{id}/analisis', [OrdenRayosXController::class, 'storeAnalisis'])->name('rayosx.storeAnalisis');


Route::get('/rayosx/{id}', [OrdenRayosXController::class, 'show'])->name('rayosx.show');

Route::resource('farmacias', FarmaciaController::class);

Route::post('/farmacias/foto-temporal', [FarmaciaController::class, 'fotoTemporal'])->name('farmacias.foto-temporal');

//factura
Route::post('facturas/consulta', [FacturaController::class, 'generarDesdeConsulta'])->name('facturas.consulta');
Route::post('facturas/rayos-x', [FacturaController::class, 'generarDesdeRayosX'])->name('facturas.rayos-x');
Route::get('/factura/{factura}', [FacturaController::class, 'show'])->name('factura.show');
Route::post('/facturas/rayos-x', [FacturaController::class, 'rayosX'])->name('facturas.rayos-x');
Route::get('/facturas/{id}', [FacturaController::class, 'show'])->name('facturas.show');



//para mensajes de aviso
// Cuando no haya receta
// Mostrar receta de una consulta

// Mostrar receta según la consulta
Route::get('/recetas/{consulta}', [RecetaController::class, 'show'])
    ->name('recetas.show');

// Cuando no haya receta
Route::get('/recetas/no-disponible/{consulta}', function ($consultaId) {
    $consulta = App\Models\Consulta::findOrFail($consultaId);
    return view('recetas.no-disponible', compact('consulta'));
})->name('recetas.no-disponible');


// ----------------------
// Exámenes
// ----------------------

// Mostrar examen según la consulta
Route::get('/examenes/{consulta}', [ExamenController::class, 'show'])
    ->name('examenes.show');

// Cuando no haya examen
Route::get('/examenes/no-disponible/{consulta}', function ($consultaId) {
    $consulta = App\Models\Consulta::findOrFail($consultaId);
    return view('examenes.no-disponible', compact('consulta'));
})->name('examenes.no-disponible');


//EMERGENCIAS
use App\Http\Controllers\EmergenciaController;
//aqui modifique solo esta
Route::resource('emergencias', EmergenciaController::class);
Route::get('/emergencias', [EmergenciaController::class, 'index'])->name('emergencias.index');
Route::get('/emergencias/create', [EmergenciaController::class, 'create'])->name('emergencias.create');
Route::post('/emergencias/store', [EmergenciaController::class, 'store'])->name('emergencias.store');
Route::get('/emergencias/{id}', [EmergenciaController::class, 'show'])->name('emergencias.show');
Route::get('/emergencias/{id}/detalle', [EmergenciaController::class, 'detalle'])->name('emergencias.detalle');

use App\Http\Controllers\HospitalizacionController;

Route::get('/hospitalizaciones/create/{emergencia_id}', [HospitalizacionController::class, 'create'])
    ->name('hospitalizaciones.create');

Route::post('/hospitalizaciones', [HospitalizacionController::class, 'store'])->name('hospitalizaciones.store');
Route::get('/hospitalizaciones/{hospitalizacion}/imprimir', [HospitalizacionController::class, 'imprimir'])->name('hospitalizaciones.imprimir');


Route::get('hospitalizaciones/create/{emergencia_id?}', [HospitalizacionController::class, 'create'])->name('hospitalizaciones.create');




// EMERGENCIAS

//rutas para psicologia
use App\Http\Controllers\SesionPsicologicaController;

// Rutas para sesiones psicológicas
Route::resource('sesiones', SesionPsicologicaController::class);


//Inventario
use App\Http\Controllers\InventarioController;

Route::get('/inventario/crear', [InventarioController::class, 'create'])->name('inventario.create');
Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::get('/inventario/{inventario}', [InventarioController::class, 'show'])->name('inventario.show');
Route::get('/inventario/{inventario}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
Route::put('/inventario/{id}', [InventarioController::class, 'update'])->name('inventario.update');
Route::get('/inventario/{id}', [InventarioController::class, 'show'])->name('inventario.show');
Route::post('/inventario/verificar-duplicado', [InventarioController::class, 'verificarDuplicado'])
    ->name('inventario.verificarDuplicado');

Route::post('/inventario/generar-codigo', [InventarioController::class, 'generarCodigo'])
    ->name('inventario.generarCodigo');



    use App\Http\Controllers\ControlPrenatalController;
    
    // Ruta principal de Ginecología (Index/Dashboard)
    Route::get('/ginecologia', [ControlPrenatalController::class, 'indexGinecologia'])->name('ginecologia.index');
    
    // Rutas de Controles Prenatales
    Route::get('/controles-prenatales', [ControlPrenatalController::class, 'index'])->name('controles-prenatales.index');
    Route::get('/controles-prenatales/crear', [ControlPrenatalController::class, 'create'])->name('controles-prenatales.create');
    Route::post('/controles-prenatales', [ControlPrenatalController::class, 'store'])->name('controles-prenatales.store');
    Route::get('/controles-prenatales/{controlPrenatal}', [ControlPrenatalController::class, 'show'])->name('controles-prenatales.show');
    Route::get('/controles-prenatales/{controlPrenatal}/editar', [ControlPrenatalController::class, 'edit'])->name('controles-prenatales.edit');
    Route::put('/controles-prenatales/{controlPrenatal}', [ControlPrenatalController::class, 'update'])->name('controles-prenatales.update');
    Route::delete('/controles-prenatales/{controlPrenatal}', [ControlPrenatalController::class, 'destroy'])->name('controles-prenatales.destroy');
    

Route::post('/sesiones/limpiar-archivo', function(){
    session()->forget('archivo_temporal');
    return response()->json(['ok'=>true]);
})->name('sesiones.limpiarArchivo');


//ultrasonidos
use App\Http\Controllers\UltrasonidoOrderController;
Route::prefix('ultrasonidos')->name('ultrasonidos.')->group(function() {
    Route::get('/', [UltrasonidoOrderController::class, 'index'])->name('index');
    Route::get('/create', [UltrasonidoOrderController::class, 'create'])->name('create');
    Route::post('/', [UltrasonidoOrderController::class, 'store'])->name('store');
    Route::get('/{ultrasonido}', [UltrasonidoOrderController::class, 'show'])->name('show');

});
Route::get('/ultrasonidos/analisis/{id}', [UltrasonidoOrderController::class, 'analisis'])
     ->name('ultrasonidos.analisis');


// Guardar el análisis
Route::post('/ultrasonidos/analisis/{id}', [UltrasonidoOrderController::class, 'storeAnalisis'])
    ->name('ultrasonidos.storeAnalisis');

    Route::get('/ultrasonidos/analisis/{id}', [UltrasonidoOrderController::class, 'analisis'])
    ->name('ultrasonidos.analisis');

Route::post('/ultrasonidos/guardar-analisis/{id}', [UltrasonidoOrderController::class, 'guardarAnalisis'])
    ->name('ultrasonidos.guardarAnalisis');


    
Route::get('/ginecologia', [ControlPrenatalController::class, 'index'])->name('ginecologia.index');
