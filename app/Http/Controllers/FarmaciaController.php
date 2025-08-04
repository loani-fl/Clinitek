<?php

namespace App\Http\Controllers;
use App\Models\Farmacia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FarmaciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Farmacia::query();

    if ($request->filled('filtro')) {
        $search = $request->filtro;
        $query->where(function($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('ubicacion', 'like', "%{$search}%");
        });
    }

    $farmacias = $query->orderBy('nombre')->paginate(2);

    $farmacias->appends($request->only('filtro'));

    if ($request->ajax()) {
        $html = view('farmacias.partials.tabla', compact('farmacias'))->render();

        return response()->json([
            'html' => $html,
            'total' => $farmacias->total(),
            'all' => Farmacia::count(),
        ]);
    }

    return view('farmacias.index', compact('farmacias'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('farmacias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s\-]+$/u'
            ],
            'ubicacion' => [
                'required',
                'string',
                'max:255',
            ],
            'telefono' => [
                'required',
                'digits:8', // Exactamente 8 dígitos
                'regex:/^[2389][0-9]{7}$/', // Debe comenzar con 2, 3, 8 o 9
                'unique:farmacias,telefono'
            ],

            'horario' => [
                'required',
                'string',
                'max:200',
                'regex:/^[\pL0-9\s:\-,\.]+$/u'
            ],
            'descripcion' => [
                'required',
                'string',
                'max:500',
            ],
            'descuento' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'foto' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'pagina_web' => [
                'nullable',
                'url',
                'max:255'
            ],

        ], [
            // NOMBRE
            'nombre.required' => 'El nombre de la farmacia es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',

            // UBICACIÓN
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.string' => 'La ubicación debe ser texto válido.',
            'ubicacion.max' => 'La ubicación no debe superar los 255 caracteres.',

            // TELÉFONO
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este teléfono ya está registrado.',

            // HORARIO
            'horario.required' => 'El horario es obligatorio.',
            'horario.string' => 'El horario debe ser texto válido.',
            'horario.max' => 'El horario no debe superar los 100 caracteres.',
            'horario.regex' => 'El horario contiene caracteres inválidos. Usa solo letras, números y signos comunes.',

            // DESCRIPCIÓN
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto válido.',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres.',

            // DESCUENTO
            'descuento.required' => 'El descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser un número.',
            'descuento.between' => 'El descuento debe estar entre 0 y 100%.',

            // FOTO
            'foto.required' => 'La foto es obligatoria.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe estar en formato JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La foto no debe pesar más de 2 MB.',

            // PÁGINA WEB

            'pagina_web.url' => 'La página web debe ser una URL válida (ej. https://ejemplo.com).',
            'pagina_web.max' => 'La URL no debe exceder los 255 caracteres.',

        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('farmacias', 'public');
            $data['foto'] = $fotoPath;
        }

        Farmacia::create($data);

        return redirect()->route('farmacias.index')->with('success', 'Farmacia registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */

    public function show(Farmacia $farmacia)
    {
        return view('farmacias.show', compact('farmacia'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmacia = Farmacia::findOrFail($id);
        return view('farmacias.edit', compact('farmacia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $farmacia = Farmacia::findOrFail($id);

        $request->validate([
            // NOMBRE
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s\-]+$/u'
            ],

            // UBICACIÓN
            'ubicacion' => [
                'required',
                'string',
                'max:255',
            ],

            // TELÉFONO
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[2389][0-9]{7}$/',
                Rule::unique('farmacias', 'telefono')->ignore($farmacia->id),
            ],

            // HORARIO
            'horario' => [
                'required',
                'string',
                'max:200',
                'regex:/^[\pL0-9\s:\-,\.]+$/u'
            ],

            // DESCRIPCIÓN
            'descripcion' => [
                'required',
                'string',
                'max:500',
            ],

            // DESCUENTO
            'descuento' => [
                'required',
                'numeric',
                'between:0,100',
            ],

            // FOTO (opcional en edit)
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // PÁGINA WEB (opcional)
            'pagina_web' => [
                'nullable',
                'url',
                'max:255'
            ],

        ], [
            // NOMBRE
            'nombre.required' => 'El nombre de la farmacia es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',

            // UBICACIÓN
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.string' => 'La ubicación debe ser texto válido.',
            'ubicacion.max' => 'La ubicación no debe superar los 255 caracteres.',

            // TELÉFONO
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este teléfono ya está registrado.',

            // HORARIO
            'horario.required' => 'El horario es obligatorio.',
            'horario.string' => 'El horario debe ser texto válido.',
            'horario.max' => 'El horario no debe superar los 100 caracteres.',
            'horario.regex' => 'El horario contiene caracteres inválidos. Usa solo letras, números y signos comunes.',

            // DESCRIPCIÓN
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto válido.',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres.',

            // DESCUENTO
            'descuento.required' => 'El descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser un número.',
            'descuento.between' => 'El descuento debe estar entre 0 y 100%.',

            // FOTO
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe estar en formato JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La foto no debe pesar más de 2 MB.',

            // PÁGINA WEB
            'pagina_web.url' => 'La página web debe ser una URL válida (ej. https://ejemplo.com).',
            'pagina_web.max' => 'La URL no debe exceder los 255 caracteres.',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($farmacia->foto && \Storage::exists($farmacia->foto)) {
                \Storage::delete($farmacia->foto);
            }
            $data['foto'] = $request->file('foto')->store('farmacias', 'public');
        }

        $farmacia->update($data);

        return redirect()->route('farmacias.index')->with('success', 'Farmacia actualizada correctamente.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
