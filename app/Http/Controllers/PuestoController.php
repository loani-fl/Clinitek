<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    // Mostrar la lista de puestos
  public function index()
{
   $puestos = Puesto::orderBy('id', 'asc')->paginate(10);
 // 8 por página
    return view('puestos.index', compact('puestos'));
}


    // Mostrar el formulario de creación
    public function create()
    {
        return view('puestos.create');
    }

    // Guardar un nuevo puesto
    public function store(Request $request)
    {
       $request->validate([
    'nombre' => 'required|string|max:255|unique:puestos,nombre',
    'codigo' => 'required|string|max:255|unique:puestos,codigo',
    'area' => 'required|string',
    'sueldo' => 'required|numeric',
    'funcion' => 'required|string',
   

    ],[
    'nombre.unique' => 'El nombre del puesto ya existe. Por favor elige otro.',
    'codigo.unique' => 'Este código ya está en uso.',
]);



Puesto::create([
    'nombre' => $request->nombre,
    'codigo' => $request->codigo,
    'area' => $request->area,   // <---- Aquí guardas el área como texto
    'sueldo' => $request->sueldo,
    'funcion' => $request->funcion,
  
]);


        return redirect()->route('puestos.index')->with('success', 'Puesto creado exitosamente.');
    }

    // Mostrar el formulario de edición
    public function edit(Puesto $puesto)
    {
        return view('puestos.edit', compact('puesto'));
    }

    // Actualizar un puesto existente
   public function update(Request $request, Puesto $puesto)
{
    $request->validate([
        'nombre' => 'required|string|max:255|unique:puestos,nombre,' . $puesto->id,
        'codigo' => 'required|string|max:255|unique:puestos,codigo,' . $puesto->id,
        'area' => 'required|string',
        'sueldo' => 'required|numeric',
        'funcion' => 'required|string',
    ], [
        'nombre.unique' => 'El nombre del puesto ya existe. Por favor elige otro.',
        'codigo.unique' => 'Este código ya está en uso.',
    ]);

    $puesto->update([
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'area' => $request->area,
        'sueldo' => $request->sueldo,
        'funcion' => $request->funcion,
    ]);

    return redirect()->route('puestos.index')->with('success', 'Puesto actualizado correctamente.');
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

