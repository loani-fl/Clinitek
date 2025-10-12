<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function create()
    {
        return view('inventario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:200',
            'fecha_ingreso' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
        ], [
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.string' => 'El código debe ser texto válido.',
            'codigo.max' => 'El código no puede tener más de 20 caracteres.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa.',
            'precio_unitario.required' => 'Debe ingresar el precio.',
            'precio_unitario.numeric' => 'El precio debe ser un número válido.',
            'precio_unitario.min' => 'El precio no puede ser negativo.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'observaciones.required' => 'Las observaciones son obligatorias.',
            'fecha_ingreso.required' => 'Debe ingresar la fecha de ingreso.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento no es válida.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
        ]);
        
        Inventario::create($request->all());

        return redirect()->route('inventario.index')
            ->with('success', 'Producto registrado correctamente en el inventario.');
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
    
        $inventarios = Inventario::when($query, function ($q) use ($query) {
            $query = strtolower($query); // minúsculas para columnas de texto
            $q->where('codigo', 'like', "%{$query}%") // funciona con letras o números
              ->orWhereRaw('LOWER(nombre) LIKE ?', ["%{$query}%"])
              ->orWhereRaw('LOWER(categoria) LIKE ?', ["%{$query}%"]);
        })
        ->orderBy('id', 'desc')
        ->paginate(2)
        ->appends($request->all());
    
        if ($request->ajax()) {
            $html = view('inventario.tabla', compact('inventarios'))->render();
            $pagination = view('inventario.custom-pagination', compact('inventarios'))->render();
    
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $inventarios->total(),
                'from' => $inventarios->firstItem(),
                'to' => $inventarios->lastItem(),
            ]);
        }
    
        return view('inventario.index', compact('inventarios'));
    }
    
    public function show($id)
    {
        $inventario = Inventario::findOrFail($id);
        return view('inventario.show', compact('inventario'));
    }

    public function edit($id)
    {
        $inventario = Inventario::findOrFail($id);
        return view('inventario.edit', compact('inventario'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'categoria' => 'required|string|max:100',
        'cantidad' => 'required|integer|min:0',
        'precio_unitario' => 'required|numeric|min:0',
        'descripcion' => 'required|string|max:200',
        'observaciones' => 'required|string|max:200',
        'fecha_ingreso' => 'required|date',
        'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
    ], [
        'nombre.required' => 'El nombre del producto es obligatorio.',
        'categoria.required' => 'Debe seleccionar una categoría.',
        'cantidad.required' => 'Debe ingresar una cantidad.',
        'precio_unitario.required' => 'Debe ingresar el precio.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'observaciones.required' => 'Las observaciones son obligatorias.',
        'fecha_ingreso.required' => 'Debe ingresar la fecha de ingreso.',
        'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
    ]);

    $inventario = Inventario::findOrFail($id);
    $inventario->update($request->all());

    return redirect()->route('inventario.index')
        ->with('success', 'Producto actualizado correctamente en el inventario.');
}
}
