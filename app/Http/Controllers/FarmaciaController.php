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

            if (is_numeric($search)) {
                $query->where('descuento', $search);
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('departamento', 'like', "%{$search}%")
                      ->orWhere('ciudad', 'like', "%{$search}%");
                });
            }
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
        $ciudadesPorDepartamento = [
            "Atlántida" => [
                "La Ceiba", "El Porvenir", "Tela", "Jutiapa",
                "La Masica", "San Francisco", "Arizona", "Esparta"
            ],
            "Choluteca" => [
                "Choluteca", "Apacilagua", "Concepción de María", "Duyure",
                "El Corpus", "El Triunfo", "Marcovia", "Morolica",
                "Namasigüe", "Orocuina", "Pespire", "San Antonio de Flores",
                "San Isidro", "San José", "San Marcos de Colón", "Santa Ana de Yusguare"
            ],
            "Colón" => [
                "Trujillo", "Balfate", "Iriona", "Limón",
                "Sabá", "Santa Fe", "Santa Rosa de Aguán", "Sonaguera",
                "Tocoa", "Bonito Oriental"
            ],
            "Comayagua" => [
                "Comayagua", "Ajuterique", "El Rosario", "Esquías",
                "Humuya", "La Libertad", "Lamaní", "La Trinidad",
                "Lejamaní", "Meámbar", "Minas de Oro", "Ojos de Agua",
                "San Jerónimo", "San José de Comayagua", "San José del Potrero",
                "San Luis"
            ],
            "Copán" => [
                "Santa Rosa de Copán", "Cabañas", "Concepción", "Copán Ruinas",
                "Corquín", "Cucuyagua", "Dolores", "Dulce Nombre",
                "El Paraíso", "Florida", "La Jigua", "La Unión",
                "Nueva Arcadia", "San Agustín", "San Antonio", "San Jerónimo",
                "San José", "San Juan de Opoa", "San Nicolás", "San Pedro",
                "Santa Rita", "Trinidad", "Veracruz"
            ],
            "Cortés" => [
                "San Pedro Sula", "Choloma", "Omoa", "Pimienta",
                "Potrerillos", "Puerto Cortés", "San Antonio de Cortés",
                "San Francisco de Yojoa", "San Manuel", "Santa Cruz de Yojoa",
                "Villanueva", "La Lima"
            ],
            "El Paraíso" => [
                "Yuscarán", "Alauca", "Danlí", "El Paraíso",
                "Güinope", "Jacaleapa", "Liure", "Morocelí",
                "Oropolí", "Potrerillos", "San Antonio de Flores",
                "San Lucas", "San Matías", "Soledad", "Teupasenti",
                "Texiguat", "Trojes", "Vado Ancho", "Yauyupe"
            ],
            "Francisco Morazán" => [
                "Tegucigalpa (Distrito Central)", "Alubarén", "Cedros",
                "Curarén", "El Porvenir", "Guaimaca", "La Libertad",
                "La Venta", "Lepaterique", "Maraita", "Marale",
                "Nueva Armenia", "Ojojona", "Orica", "Reitoca",
                "Sabanagrande", "San Antonio de Oriente", "San Buenaventura",
                "San Ignacio", "San Juan de Flores (Cantarranas)",
                "San Miguelito", "Santa Ana", "Santa Lucía",
                "Talanga", "Tatumbla", "Valle de Ángeles", "Villa de San Francisco",
                "Vallecillo"
            ],
            "Gracias a Dios" => [
                "Puerto Lempira", "Brus Laguna", "Ahuas", "Juan Francisco Bulnes",
                "Villeda Morales", "Wampusirpe"
            ],
            "Intibucá" => [
                "La Esperanza", "Camasca", "Colomoncagua", "Concepción",
                "Dolores", "Intibucá", "Jesús de Otoro", "Magdalena",
                "Masaguara", "San Antonio", "San Isidro", "San Juan de Flores",
                "San Marcos de la Sierra", "San Miguel Guancapla", "Santa Lucía",
                "Yamaranguila", "San Francisco Opalaca"
            ],
            "Islas de la Bahía" => [
                "Roatán", "Guanaja", "José Santos Guardiola", "Utila"
            ],
            "La Paz" => [
                "La Paz", "Aguanqueterique", "Cabañas", "Cane",
                "Chinacla", "Guajiquiro", "Lauterique", "Marcala",
                "Mercedes de Oriente", "Opatoro", "San Antonio del Norte",
                "San José", "San Juan", "San Pedro de Tutule",
                "Santa Ana", "Santa Elena", "Santa María", "Santiago de Puringla",
                "Yarula"
            ],
            "Lempira" => [
                "Gracias", "Belén", "Candelaria", "Cololaca", "Erandique",
                "Gualcince", "Guarita", "La Campa", "La Iguala", "La Unión",
                "La Virtud", "Las Flores", "Lepaera", "Mapulaca", "Piraera",
                "San Andrés", "San Francisco", "San Juan Guarita",
                "San Manuel Colohete", "San Rafael", "San Sebastián",
                "Santa Cruz", "Talgua", "Tambla", "Tomalá",
                "Valladolid", "Virginia", "San Marcos de Caiquín"
            ],
            "Ocotepeque" => [
                "Nueva Ocotepeque", "Belén Gualcho", "Concepción", "Dolores Merendón",
                "Fraternidad", "La Encarnación", "La Labor", "Lucerna",
                "Mercedes", "San Fernando", "San Francisco del Valle",
                "San Jorge", "San Marcos", "Santa Fe", "Sensenti", "Sinuapa"
            ],
            "Olancho" => [
                "Juticalpa", "Campamento", "Catacamas", "Concordia",
                "Dulce Nombre de Culmí", "El Rosario", "Esquipulas del Norte", "Gualaco",
                "Guarizama", "Guata", "Guayape", "Jano", "La Unión", "Mangulile",
                "Manto", "Salamá", "San Esteban", "San Francisco de Becerra",
                "San Francisco de la Paz", "Santa María del Real", "Silca", "Yocón"
            ],
            "Santa Bárbara" => [
                "Santa Bárbara", "Arada", "Atima", "Azacualpa", "Ceguaca",
                "Colinas", "Concepción del Norte", "Concepción del Sur",
                "Chinda", "El Níspero", "Gualala", "Ilama", "Macuelizo",
                "Naranjito", "Nueva Celilac", "Petoa", "Protección", "Quimistán",
                "San Francisco de Ojuera", "San Luis", "San Marcos", "San Nicolás",
                "San Pedro Zacapa", "Santa Rita", "San Vicente Centenario",
                "Trinidad", "Las Vegas", "Nueva Frontera"
            ],
            "Valle" => [
                "Nacaome", "Alianza", "Amapala", "Aramecina", "Caridad",
                "Goascorán", "Langue", "San Francisco de Coray", "San Lorenzo"
            ],
            "Yoro" => [
                "Yoro", "Arenal", "El Negrito", "El Progreso", "Olanchito",
                "Santa Rita", "Sulaco", "Victoria", "Yorito", "Jocon", "Morazán"
            ]
        ];
        return view('farmacias.create', compact('ciudadesPorDepartamento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('temp', 'public');
            session(['foto_temporal' => $path]);
        }
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
                Rule::unique('farmacias', 'nombre')

            ],


            'telefono' => [
                'required',
                'digits:8', // Exactamente 8 dígitos
                'regex:/^[2389][0-9]{7}$/', // Debe comenzar con 2, 3, 8 o 9
                Rule::unique('farmacias', 'telefono')
            ],
            'departamento' => [
                'required',
                'string',
                'max:100'
            ],
            'ciudad' => [
                'required',
                'string',
                'max:100'
            ],
            'direccion' => [
                'required',
                'string',
                'max:255',
                  'regex:/^[A-Za-z0-9\s\.,#-]+$/'
            ],

            'descripcion' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9\s\.,%áéíóúÁÉÍÓÚñÑ()-]+$/u'
            ],
            'descuento' => [
                'required',
                'numeric',
                'between:1,100',

            ],
            'foto' => [
                session('foto_temporal') ? 'nullable' : 'required', // Si ya hay foto temporal, no la requiere
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'pagina_web' => [
                'nullable',
                'url',
                'max:255',
                Rule::unique('farmacias', 'pagina_web')
            ],

        ], [
            // NOMBRE
            'nombre.required' => 'El nombre de la farmacia es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'nombre.unique' => 'Este nombre de farmacia ya está registrado.',

            // DIRECCION
            'direccion.required' => 'La direccion es obligatoria.',
            'direcccion.string' => 'La direccion debe ser texto válido.',
            'direccion.max' => 'La direccion no debe superar los 255 caracteres.',



            // TELÉFONO
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este teléfono ya está registrado.',



            // DESCRIPCIÓN
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser texto válido.',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres.',

            // DESCUENTO
            'descuento.required' => 'El descuento es obligatorio.',
            'descuento.numeric' => 'El descuento debe ser un número.',
            'descuento.between' => 'El descuento debe estar entre 1 y 100%.',

            // FOTO
            'foto.required' => 'La foto es obligatoria.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe estar en formato JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La foto no debe pesar más de 2 MB.',

            // PÁGINA WEB

            'pagina_web.url' => 'La página web debe ser una URL válida (ej. https://ejemplo.com).',
            'pagina_web.max' => 'La URL no debe exceder los 255 caracteres.',
            'pagina_web.unique' => 'Esta página web ya está registrada.',

            'departamento.required' => 'El departamento es obligatorio.',
            'ciudad.required' => 'La ciudad es obligatoria.',

        ]);

        $data = $request->except('foto');

        if (session('foto_temporal')) {
            $tempPath = session('foto_temporal');
            $finalPath = 'farmacias/' . basename($tempPath);
            \Storage::disk('public')->move($tempPath, $finalPath);
            $data['foto'] = $finalPath;

            // Limpiar sesión
            session()->forget('foto_temporal');
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

        // Lista de departamentos
        $departamento = [
            'Atlántida', 'Choluteca', 'Colón', 'Comayagua', 'Copán', 'Cortés',
            'El Paraíso', 'Francisco Morazán', 'Gracias a Dios', 'Intibucá',
            'Islas de la Bahía', 'La Paz', 'Lempira', 'Ocotepeque', 'Olancho',
            'Santa Bárbara', 'Valle', 'Yoro'
        ];
        $ciudad = [
            "Atlántida" => [
                "La Ceiba", "El Porvenir", "Tela", "Jutiapa",
                "La Masica", "San Francisco", "Arizona", "Esparta"
            ],
            "Choluteca" => [
                "Choluteca", "Apacilagua", "Concepción de María", "Duyure",
                "El Corpus", "El Triunfo", "Marcovia", "Morolica",
                "Namasigüe", "Orocuina", "Pespire", "San Antonio de Flores",
                "San Isidro", "San José", "San Marcos de Colón", "Santa Ana de Yusguare"
            ],
            "Colón" => [
                "Trujillo", "Balfate", "Iriona", "Limón",
                "Sabá", "Santa Fe", "Santa Rosa de Aguán", "Sonaguera",
                "Tocoa", "Bonito Oriental"
            ],
            "Comayagua" => [
                "Comayagua", "Ajuterique", "El Rosario", "Esquías",
                "Humuya", "La Libertad", "Lamaní", "La Trinidad",
                "Lejamaní", "Meámbar", "Minas de Oro", "Ojos de Agua",
                "San Jerónimo", "San José de Comayagua", "San José del Potrero",
                "San Luis"
            ],
            "Copán" => [
                "Santa Rosa de Copán", "Cabañas", "Concepción", "Copán Ruinas",
                "Corquín", "Cucuyagua", "Dolores", "Dulce Nombre",
                "El Paraíso", "Florida", "La Jigua", "La Unión",
                "Nueva Arcadia", "San Agustín", "San Antonio", "San Jerónimo",
                "San José", "San Juan de Opoa", "San Nicolás", "San Pedro",
                "Santa Rita", "Trinidad", "Veracruz"
            ],
            "Cortés" => [
                "San Pedro Sula", "Choloma", "Omoa", "Pimienta",
                "Potrerillos", "Puerto Cortés", "San Antonio de Cortés",
                "San Francisco de Yojoa", "San Manuel", "Santa Cruz de Yojoa",
                "Villanueva", "La Lima"
            ],
            "El Paraíso" => [
                "Yuscarán", "Alauca", "Danlí", "El Paraíso",
                "Güinope", "Jacaleapa", "Liure", "Morocelí",
                "Oropolí", "Potrerillos", "San Antonio de Flores",
                "San Lucas", "San Matías", "Soledad", "Teupasenti",
                "Texiguat", "Trojes", "Vado Ancho", "Yauyupe"
            ],
            "Francisco Morazán" => [
                "Tegucigalpa (Distrito Central)", "Alubarén", "Cedros",
                "Curarén", "El Porvenir", "Guaimaca", "La Libertad",
                "La Venta", "Lepaterique", "Maraita", "Marale",
                "Nueva Armenia", "Ojojona", "Orica", "Reitoca",
                "Sabanagrande", "San Antonio de Oriente", "San Buenaventura",
                "San Ignacio", "San Juan de Flores (Cantarranas)",
                "San Miguelito", "Santa Ana", "Santa Lucía",
                "Talanga", "Tatumbla", "Valle de Ángeles", "Villa de San Francisco",
                "Vallecillo"
            ],
            "Gracias a Dios" => [
                "Puerto Lempira", "Brus Laguna", "Ahuas", "Juan Francisco Bulnes",
                "Villeda Morales", "Wampusirpe"
            ],
            "Intibucá" => [
                "La Esperanza", "Camasca", "Colomoncagua", "Concepción",
                "Dolores", "Intibucá", "Jesús de Otoro", "Magdalena",
                "Masaguara", "San Antonio", "San Isidro", "San Juan de Flores",
                "San Marcos de la Sierra", "San Miguel Guancapla", "Santa Lucía",
                "Yamaranguila", "San Francisco Opalaca"
            ],
            "Islas de la Bahía" => [
                "Roatán", "Guanaja", "José Santos Guardiola", "Utila"
            ],
            "La Paz" => [
                "La Paz", "Aguanqueterique", "Cabañas", "Cane",
                "Chinacla", "Guajiquiro", "Lauterique", "Marcala",
                "Mercedes de Oriente", "Opatoro", "San Antonio del Norte",
                "San José", "San Juan", "San Pedro de Tutule",
                "Santa Ana", "Santa Elena", "Santa María", "Santiago de Puringla",
                "Yarula"
            ],
            "Lempira" => [
                "Gracias", "Belén", "Candelaria", "Cololaca", "Erandique",
                "Gualcince", "Guarita", "La Campa", "La Iguala", "La Unión",
                "La Virtud", "Las Flores", "Lepaera", "Mapulaca", "Piraera",
                "San Andrés", "San Francisco", "San Juan Guarita",
                "San Manuel Colohete", "San Rafael", "San Sebastián",
                "Santa Cruz", "Talgua", "Tambla", "Tomalá",
                "Valladolid", "Virginia", "San Marcos de Caiquín"
            ],
            "Ocotepeque" => [
                "Nueva Ocotepeque", "Belén Gualcho", "Concepción", "Dolores Merendón",
                "Fraternidad", "La Encarnación", "La Labor", "Lucerna",
                "Mercedes", "San Fernando", "San Francisco del Valle",
                "San Jorge", "San Marcos", "Santa Fe", "Sensenti", "Sinuapa"
            ],
            "Olancho" => [
                "Juticalpa", "Campamento", "Catacamas", "Concordia",
                "Dulce Nombre de Culmí", "El Rosario", "Esquipulas del Norte", "Gualaco",
                "Guarizama", "Guata", "Guayape", "Jano", "La Unión", "Mangulile",
                "Manto", "Salamá", "San Esteban", "San Francisco de Becerra",
                "San Francisco de la Paz", "Santa María del Real", "Silca", "Yocón"
            ],
            "Santa Bárbara" => [
                "Santa Bárbara", "Arada", "Atima", "Azacualpa", "Ceguaca",
                "Colinas", "Concepción del Norte", "Concepción del Sur",
                "Chinda", "El Níspero", "Gualala", "Ilama", "Macuelizo",
                "Naranjito", "Nueva Celilac", "Petoa", "Protección", "Quimistán",
                "San Francisco de Ojuera", "San Luis", "San Marcos", "San Nicolás",
                "San Pedro Zacapa", "Santa Rita", "San Vicente Centenario",
                "Trinidad", "Las Vegas", "Nueva Frontera"
            ],
            "Valle" => [
                "Nacaome", "Alianza", "Amapala", "Aramecina", "Caridad",
                "Goascorán", "Langue", "San Francisco de Coray", "San Lorenzo"
            ],
            "Yoro" => [
                "Yoro", "Arenal", "El Negrito", "El Progreso", "Olanchito",
                "Santa Rita", "Sulaco", "Victoria", "Yorito", "Jocon", "Morazán"
            ]
        ];

        return view('farmacias.edit', compact('farmacia', 'departamento', 'ciudad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $farmacia = Farmacia::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Guardar foto temporal en storage/public/temp
            $path = $request->file('foto')->store('temp', 'public');
            session(['foto_temporal' => $path]);
        }

        $request->validate([
            // NOMBRE
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
                Rule::unique('farmacias', 'nombre')->ignore($farmacia->id),
            ],

            // DEPARTAMENTO
            'departamento' => [
                'required',
                'string',
                'max:100'
            ],

            // CIUDAD
            'ciudad' => [
                'required',
                'string',
                'max:100'
            ],

            // DIRECCIÓN
            'direccion' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9\s\.,#-]+$/'
            ],

            // TELÉFONO
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[2389][0-9]{7}$/',
                Rule::unique('farmacias', 'telefono')->ignore($farmacia->id),
            ],



            // DESCRIPCIÓN
            'descripcion' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9\s\.,%áéíóúÁÉÍÓÚñÑ()-]+$/u'
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
                'max:255',
                Rule::unique('farmacias', 'pagina_web')->ignore($farmacia->id),
            ],

        ], [
            // NOMBRE
            'nombre.required' => 'El nombre de la farmacia es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'nombre.unique' => 'Este nombre de farmacia ya está registrado.',


            // DEPARTAMENTO
            'departamento.required' => 'El departamento es obligatorio.',
            'departamento.string' => 'El departamento debe ser texto válido.',

            // CIUDAD
            'ciudad.required' => 'La ciudad es obligatoria.',
            'ciudad.string' => 'La ciudad debe ser texto válido.',


            // DIRECCIÓN
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser texto válido.',
            'direccion.max' => 'La dirección no debe superar los 255 caracteres.',


            // TELÉFONO
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este teléfono ya está registrado.',


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
            'pagina_web.unique' => 'Esta página web ya está registrada.',

        ]);

        $data = $request->except('foto');


        if ($request->restablecer_foto == "1") {
            // El usuario pidió restablecer → dejamos la foto original
            $data['foto'] = $farmacia->getOriginal('foto');
        } elseif ($request->hasFile('foto')) {
            // Subió nueva foto → reemplazamos
            if ($farmacia->foto && \Storage::exists($farmacia->foto)) {
                \Storage::delete($farmacia->foto);
            }
            $data['foto'] = $request->file('foto')->store('farmacias', 'public');
        }

        $farmacia->update($data);
        // 🔥 Limpiar foto temporal para que no aparezca al volver a editar
        session()->forget('foto_temporal');

        return redirect()->route('farmacias.index')->with('success', 'Farmacia actualizada correctamente.');
    }



    /**
     * Remove the specified resource from storage.
     */


    public function fotoTemporal(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar archivo temporal en storage/app/public/temp
        $path = $request->file('foto')->store('temp', 'public');

        // Guardar la ruta temporal en la sesión
        session(['foto_temporal' => $path]);

        // Devolver la URL pública
        return response()->json([
            'url' => asset('storage/' . $path),
            'path' => $path
        ]);
    }


}
