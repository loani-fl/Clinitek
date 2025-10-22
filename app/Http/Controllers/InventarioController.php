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
            'unidad' => 'required|string|in:Cajas,Frascos,Sobres,Paquetes,Unidades,Litros,Mililitros,Tabletas,Ampollas',
            'precio_unitario' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:200',
            'fecha_ingreso' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa.',
            'unidad.required' => 'Debe seleccionar una unidad.',
            'unidad.in' => 'La unidad seleccionada no es válida.',
            'precio_unitario.required' => 'Debe ingresar el precio.',
            'precio_unitario.numeric' => 'El precio debe ser un número válido.',
            'precio_unitario.min' => 'El precio no puede ser negativo.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'fecha_ingreso.required' => 'Debe ingresar la fecha de ingreso.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento no es válida.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
        ]);

        // ✅ Generar código automáticamente según la categoría
        $codigo = Inventario::generarCodigoPorCategoria($request->categoria);

        // ✅ Crear el registro
        Inventario::create([
            'codigo' => $codigo,
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'cantidad' => $request->cantidad,
            'unidad' => $request->unidad,
            'precio_unitario' => $request->precio_unitario,
            'descripcion' => $request->descripcion,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);

        return redirect()->route('inventario.index')
            ->with('success', "Producto registrado correctamente con código: {$codigo}");
    }

    // ===== Método AJAX para generar código =====
    public function generarCodigo(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string',
        ]);

        $codigo = Inventario::generarCodigoPorCategoria($request->categoria);

        return response()->json(['codigo' => $codigo]);
    }

    public function index(Request $request)
    {
        $query = Inventario::query();
    
        // Filtro por búsqueda de texto
        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(codigo) LIKE ?', ["{$search}%"]) 
                  ->orWhereRaw('LOWER(nombre) LIKE ?', ["%{$search}%"]);
            });
        }
        
    
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
    
        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_ingreso', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_ingreso', '<=', $request->fecha_fin);
        }
    
        $inventarios = $query->orderBy('id', 'desc')->paginate(10)->appends($request->all());
    
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
    
        // Para mostrar el select de categorías
        $categorias = Inventario::distinct()->pluck('categoria');
    
        return view('inventario.index', compact('inventarios', 'categorias'));
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
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'unidad' => 'required|string|in:Cajas,Frascos,Sobres,Paquetes,Unidades,Litros,Mililitros,Tabletas,Ampollas',
            'precio_unitario' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:200',
            'fecha_ingreso' => 'required|date',
            'fecha_vencimiento' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && isset($request->fecha_ingreso)) {
                        $minDate = \Carbon\Carbon::parse($request->fecha_ingreso)->addMonth();
                        if (\Carbon\Carbon::parse($value)->lt($minDate)) {
                            $fail('La fecha de vencimiento debe ser al menos un mes después de la fecha de ingreso.');
                        }
                    }
                }
            ],
        ], [
            'codigo.required' => 'El código del producto es obligatorio.',
            'codigo.string' => 'El código debe ser texto válido.',
            'codigo.max' => 'El código no puede tener más de 20 caracteres.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser negativa.',
            'unidad.required' => 'Debe seleccionar una unidad.',
            'unidad.in' => 'La unidad seleccionada no es válida.',
            'precio_unitario.required' => 'Debe ingresar el precio.',
            'precio_unitario.numeric' => 'El precio debe ser un número válido.',
            'precio_unitario.min' => 'El precio no puede ser negativo.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'fecha_ingreso.required' => 'Debe ingresar la fecha de ingreso.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento no es válida.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
        ]);

        $inventario = Inventario::findOrFail($id);
        $inventario->update($request->all());

        return redirect()->route('inventario.index')
            ->with('success', 'Producto actualizado correctamente en el inventario.');
    }
}
