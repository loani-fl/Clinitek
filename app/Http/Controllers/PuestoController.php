<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    // Mostrar la lista de puestos
  public function index()
{
  $puestos = Puesto::all();
// muestra 10 por página (puedes ajustar)

 // 8 por página
    return view('puestos.index', compact('puestos'));
}


    // Mostrar el formulario de creación
public function create()
{
    return view('puestos.create');
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'codigo' => [
            'required',
            'string',
            'max:10',
            'regex:/^[A-Za-z0-9\-]+$/',
            'unique:puestos,codigo',
        ],
       'nombre' => [
    'required',
    'string',
    'max:50',
    'regex:/^[\pL\s]+$/u',
],

        
        'area' => [
            'required',
            'in:Administración,Recepción,Laboratorio,Farmacia,Enfermería,Mantenimiento',
        ],
        'sueldo' => [
            'required',
            'regex:/^\d{1,5}(\.\d{1,2})?$/',
        ],
   'funcion' => [
    'required',
    'string',
    'max:50',
    'regex:/^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$/u',
],

 // Acepta letras con tildes, números, espacios, comas y puntos
        ],
     [
        'codigo.required' => 'El código es obligatorio.',
        'codigo.max' => 'El código no debe exceder 10 caracteres.',
        'codigo.regex' => 'Solo se permiten letras, números y guiones.',
        'codigo.unique' => 'Este código ya existe.',

        'nombre.required' => 'El nombre del puesto es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 50 caracteres.',
        'nombre.regex' => 'El nombre solo debe contener letras y espacios.',

        'area.required' => 'Debe seleccionar un área.',
        'area.in' => 'El área seleccionada no es válida.',

        'sueldo.required' => 'El sueldo es obligatorio.',
        'sueldo.regex' => 'El sueldo debe tener máximo 5 dígitos enteros y 2 decimales.',

        'funcion.required' => 'La función es obligatoria.',
        'funcion.max' => 'La función no debe tener más de 50 caracteres.',
        'funcion.regex' => 'La función solo puede contener letras, números, comas, puntos y espacios.',

    ]);

    Puesto::create($validatedData);

    return back()->withInput()->with('success', '¡Puesto registrado exitosamente!');
}


// Mostrar el formulario de edición
public function edit(Puesto $puesto)
{
    return view('puestos.edit', compact('puesto'));
}

// Actualizar un puesto existente
public function update(Request $request, Puesto $puesto)
{
    $validated = $request->validate([
        'codigo' => ['required', 'string', 'max:10', 'regex:/^[A-Za-z0-9\-]+$/', "unique:puestos,codigo,{$puesto->id}"],
         'nombre' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u',
],
        'area' => ['required', 'in:Administración,Recepción,Laboratorio,Farmacia,Enfermería,Mantenimiento'],
        'sueldo' => ['required', 'regex:/^\d{1,5}(\.\d{1,2})?$/'],
        'funcion' => ['required', 'string', 'max:50', 'regex:/^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$/u'
],
        

    ], [
        'codigo.regex' => 'El código solo puede contener letras, números y guiones.',
        'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        'sueldo.required' => 'El campo sueldo es obligatorio.',
        'sueldo.regex' => 'El sueldo debe tener máximo 5 dígitos enteros y 2 decimales.',
    ]);

    // Comprobar si hay cambios
    if (
        $puesto->codigo === $validated['codigo'] &&
        $puesto->nombre === $validated['nombre'] &&
        $puesto->area === $validated['area'] &&
        (float)$puesto->sueldo === (float)$validated['sueldo'] &&
        $puesto->funcion === $validated['funcion']
    ) {
        return redirect()->route('puestos.index')
                         ->with('info', 'No se realizaron cambios.');
    }

    // Si hay cambios, actualizamos
    $puesto->update($validated);

    return redirect()->route('puestos.index')
                     ->with('success', 'Puesto actualizado correctamente.');
}





    // Eliminar un puesto
    public function destroy(Puesto $puesto)
    {
        $puesto->delete();
        return redirect()->route('puestos.index')->with('success', 'Puesto eliminado correctamente.');
    }
public function show($id)
{
    $puesto = Puesto::findOrFail($id);
    return view('puestos.show', compact('puesto'));
}




}

