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
            'cantidad' => 'required|integer|min:1|max:99999',
            'precio_unitario' => 'required|numeric|min:0.01|max:99999.99',
            'descripcion' => 'required|string|max:200',
            'fecha_ingreso' => 'required|date|before_or_equal:today|after_or_equal:' . now()->subMonths(2)->format('Y-m-d'),
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad no puede ser 0.',
            'cantidad.max' => 'La cantidad no puede superar 5 cifras.',
            'precio_unitario.required' => 'Debe ingresar el precio.',
            'precio_unitario.numeric' => 'El precio debe ser un número válido.',
            'precio_unitario.min' => 'El precio no puede ser 0.',
            'precio_unitario.max' => 'El precio no puede superar 5 cifras.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'fecha_ingreso.required' => 'Debe ingresar la fecha de ingreso.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es válida.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura.',
            'fecha_ingreso.after_or_equal' => 'La fecha de ingreso no puede ser mayor a 2 meses atrás.',
        ]);

        // Generar código automáticamente según la categoría
        $codigo = Inventario::generarCodigoPorCategoria($request->categoria);

        // Crear el registro
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

        // Filtro por búsqueda de texto (cualquier letra o número)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('nombre', 'LIKE', "%{$search}%");
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

        $inventarios = $query->orderBy('id', 'desc')->paginate(2)->appends($request->all());

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
            'cantidad' => 'required|integer|min:1|max:99999',
            'precio_unitario' => 'required|numeric|min:0.01|max:99999.99',
            'descripcion' => 'required|string|max:200',
            'fecha_ingreso' => 'required|date|before_or_equal:today|after_or_equal:' . now()->subMonths(2)->format('Y-m-d'),
        ]);

        $inventario = Inventario::findOrFail($id);

        $inventario->update($request->only([
            'codigo',
            'nombre',
            'categoria',
            'cantidad',
            'unidad',
            'precio_unitario',
            'descripcion',
            'fecha_ingreso',
        ]));

        return redirect()->route('inventario.index')
            ->with('success', 'Producto actualizado correctamente en el inventario.');
    }
}
