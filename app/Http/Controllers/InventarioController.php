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

        Inventario::create($request->all());

        return redirect()->route('inventario.index')
            ->with('success', 'Producto registrado correctamente en el inventario.');
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
    
        $inventarios = Inventario::when($query, function ($q) use ($query) {
            $q->where('nombre', 'like', "%$query%")
              ->orWhere('categoria', 'like', "%$query%");
        })
        ->orderBy('id', 'desc')
        ->paginate(3)
        ->appends($request->all()); // Mantener filtros en la paginación
    
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
}
