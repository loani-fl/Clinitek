<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    // Mostrar la lista de puestos
public function index(Request $request)
{
    try {
        $query = $request->input('search', '');
        $perPage = 3;

        $puestosQuery = Puesto::query();

        if ($query) {
            $puestosQuery->where(function ($q) use ($query) {
                $q->where('codigo', 'like', "%{$query}%")
                  ->orWhere('nombre', 'like', "%{$query}%");
            });
        }

        if ($query) {
            // Si hay búsqueda, traemos todos los resultados filtrados
            $puestos = $puestosQuery->get();
            $isSearch = true;
            $startIndex = 0; // numeración desde 0
        } else {
            // Si no hay búsqueda, usamos paginación
            $puestos = $puestosQuery->paginate($perPage)->withQueryString();
            $isSearch = false;
            $startIndex = ($puestos->currentPage() - 1) * $puestos->perPage();
        }

        // AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('puestos.partials.tabla', compact('puestos', 'startIndex', 'isSearch'))->render(),
                'pagination' => $isSearch ? '' : $puestos->links('pagination::bootstrap-5')->render(),
                
                'total' => $puestos->count(),
                'all' => Puesto::count(),
            ]);
        }

        return view('puestos.index', compact('puestos', 'startIndex', 'isSearch'));
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        abort(500, $e->getMessage());
    }
}




    // Mostrar formulario de creación
    public function create()
    {
        return view('puestos.create');
    }

    // Guardar un nuevo puesto
    public function store(Request $request)
    {
        $validatedData = $request->validate([
          'codigo' => [
    'required',
    'string',
    'max:10',
    'regex:/^[A-Za-zÑñ0-9\-]+$/',
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
                'max:300',
                'regex:/^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$/u',
            ],
        ], [
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
            'funcion.max' => 'La función no debe tener más de 300 caracteres.',
            'funcion.regex' => 'La función solo puede contener letras, números, comas, puntos y espacios.',
        ]);

        Puesto::create($validatedData);

        return redirect()->route('puestos.index')->with('success', '¡Puesto registrado exitosamente!');
    }

    // Mostrar formulario de edición
    public function edit(Puesto $puesto)
    {
        return view('puestos.edit', compact('puesto'));
    }

    // Actualizar un puesto
    public function update(Request $request, Puesto $puesto)
    {
        $validated = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Za-z0-9\-]+$/',
                "unique:puestos,codigo,{$puesto->id}",
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
                'max:300',
                'regex:/^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$/u',
            ],
        ], [
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
            'funcion.max' => 'La función no debe tener más de 300 caracteres.',
            'funcion.regex' => 'La función solo puede contener letras, números, comas, puntos y espacios.',
        ]);

        // Si no hay cambios, no hacer update
        if (
            $puesto->codigo === $validated['codigo'] &&
            $puesto->nombre === $validated['nombre'] &&
            $puesto->area === $validated['area'] &&
            (float) $puesto->sueldo === (float) $validated['sueldo'] &&
            $puesto->funcion === $validated['funcion']
        ) {
            return redirect()->route('puestos.index')->with('info', 'No se realizaron cambios.');
        }

        $puesto->update($validated);

        return redirect()->route('puestos.index')->with('success', 'Puesto actualizado correctamente.');
    }

    // Eliminar un puesto
    public function destroy(Puesto $puesto)
    {
        $puesto->delete();
        return redirect()->route('puestos.index')->with('success', 'Puesto eliminado correctamente.');
    }

    // Mostrar un puesto
    public function show($id)
    {
        $puesto = Puesto::findOrFail($id);
        return view('puestos.show', compact('puesto'));
    }
}
