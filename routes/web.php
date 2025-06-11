<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PuestoController;

use App\Http\Controllers\EmpleadosController;
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

// Ruta protegida por sesión
Route::middleware('check.sesion')->get('/empleados/visualizacion', [EmpleadoController::class, 'visualizacion'])->name('empleados.visualizacion');



// Rutas públicas de puestos
Route::resource('puestos', PuestoController::class);

// Rutas públicas de empleados
Route::resource('empleados', EmpleadosController::class);

//Rutas para Registrar empleados

Route::get('/empleados/create', [EmpleadosController::class, 'create'])->name('empleados.create');
Route::post('/empleados', [EmpleadosController::class, 'store'])->name('empleados.store');

Route::get('puestos/{puesto}', [PuestoController::class, 'show'])->name('puestos.show');



