<?php

namespace App\Http\Controllers;

use App\Models\Emergencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class EmergenciaController extends Controller
{
   

    public function create()
    {
        return view('emergencias.create');
    }


public function store(Request $request)
{
    $documentado = $request->documentado === '1';
    $anioActual = now()->year;

    $rules = [
        'documentado' => 'required|boolean',

        // Nombres y apellidos solo letras, máximo 40
        'nombres' => $documentado ? ['required','string','max:40','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'] : 'nullable',
        'apellidos' => $documentado ? ['required','string','max:40','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'] : 'nullable',

        // Identidad avanzada
       // Identidad avanzada
'identidad' => $documentado ? [
    'required',
    'digits:13',
   
    function ($attribute, $value, $fail) use ($anioActual, &$request) {
        $departamentosMunicipios = [
            '01' => 8, '02' => 10, '03' => 21, '04' => 23, '05' => 12,
            '06' => 16, '07' => 19, '08' => 28, '09' => 9, '10' => 17,
            '11' => 4, '12' => 19, '13' => 28, '14' => 16, '15' => 23,
            '16' => 28, '17' => 9, '18' => 11,
        ];

        $codigoDepartamento = substr($value, 0, 2);
        if (!array_key_exists($codigoDepartamento, $departamentosMunicipios)) {
            return $fail('El código del departamento en la identidad no es válido.');
        }

        $codigoMunicipio = (int) substr($value, 2, 2);
        $maxMunicipios = $departamentosMunicipios[$codigoDepartamento];
        if ($codigoMunicipio < 1 || $codigoMunicipio > $maxMunicipios) {
            return $fail('El código de municipio en la identidad no es válido.');
        }

        $anioNacimiento = (int) substr($value, 4, 4);
        if ($anioNacimiento < 1900 || $anioNacimiento > $anioActual) {
            return $fail('El año de nacimiento en la identidad no es válido.');
        }

        $edad = $anioActual - $anioNacimiento;

        // Permitir recién nacidos hasta 95 años
        if ($edad < 0 || $edad > 95) {
            return $fail("La edad calculada a partir de la identidad no es válida (0-95 años; actual: $edad).");
        }

        $request->merge(['edad' => $edad]);
    }
] : 'nullable',

// Edad calculada
'edad' => $documentado ? 'required|integer|min:0|max:95' : 'nullable',


        // Sexo
        'sexo' => $documentado ? ['required', Rule::in(['M','F'])] : 'nullable',

        // Teléfono avanzado
        'telefono' => $documentado ? [
            'required', 'digits:8', 'regex:/^[2389][0-9]{7}$/', 
        ] : 'nullable',

        // Foto
       // 'foto' => !$documentado ? 'nullable|image|mimes:jpg,jpeg,png|max:2048' : 'nullable',
       'foto' => !$documentado ? 'required|image|mimes:jpg,jpeg,png|max:2048' : 'nullable',


        // Motivo y dirección
        'motivo' => ['required','string','min:5','max:60','regex:/^[A-Za-z0-9.,\s]+$/'],
        'direccion' => ['required','string','min:5','max:70','regex:/^[A-Za-z0-9.,\s]+$/'],

        // Signos vitales con validaciones estrictas
        'pa' => [
            'nullable',
            function($attribute, $value, $fail) {
                if (!preg_match('/^\d{2,3}\/\d{2,3}$/', $value)) {
                    return $fail('El formato de la presión arterial debe ser XX/XX o XXX/XX.');
                }
                [$sist, $diast] = explode('/', $value);
                $sist = (int)$sist;
                $diast = (int)$diast;
                if($sist < 80 || $sist > 200) $fail('La presión sistólica debe estar entre 80 y 200 mmHg.');
if($diast < 40 || $diast > 130) $fail('La presión diastólica debe estar entre 40 y 130 mmHg.');

            }
        ],
        'fc' => [
            'nullable',
            'integer',
            function($attribute, $value, $fail) {
                if($value < 30 || $value > 200) {
                    $fail('La frecuencia cardíaca debe estar entre 30 y 200 lpm.');
                }
            }
        ],
        'temp' => [
            'nullable',
            'numeric',
            function($attribute, $value, $fail) {
                if($value < 30 || $value > 45) {
                    $fail('La temperatura debe estar entre 30°C y 45°C.');
                }
            }
        ],

        //'fecha_hora' => 'required|date',
    ];

    $messages = [
        'required' => ':attribute es obligatorio.',
        'digits' => ':attribute debe tener exactamente :digits dígitos.',
        'regex' => ':attribute contiene caracteres inválidos.',
        'min' => ':attribute debe tener al menos :min caracteres.',
        'max' => ':attribute no puede tener más de :max caracteres.',
        'numeric' => ':attribute debe ser un número válido.',
        'image' => ':attribute debe ser una imagen válida.',
        'unique' => ':attribute ya está registrado.',
    ];

    $attributes = [
        'nombres' => 'Nombres',
        'apellidos' => 'Apellidos',
        'identidad' => 'Identidad',
        'edad' => 'Edad',
        'sexo' => 'Sexo',
        'telefono' => 'Teléfono',
        'motivo' => 'Motivo',
        'direccion' => 'Dirección',
        'foto' => 'Foto del paciente',
        'pa' => 'Presión arterial',
        'fc' => 'Frecuencia cardíaca',
        'temp' => 'Temperatura',
        'fecha_hora' => 'Fecha y hora',
    ];

    $request->validate($rules, $messages, $attributes);

    // Guardar datos
    $emergencia = new Emergencia();
    $emergencia->documentado = $documentado;
    $emergencia->motivo = $request->motivo;
    $emergencia->direccion = $request->direccion;
    $emergencia->pa = $request->pa;
    $emergencia->fc = $request->fc;
    $emergencia->temp = $request->temp;
    //$emergencia->fecha_hora = $request->fecha_hora;

    // ✅ Guardar fecha y hora separadas
    $emergencia->fecha = now()->toDateString();  // YYYY-MM-DD
    $emergencia->hora = now()->format('H:i');    // HH:MM en 24h


    if ($documentado) {
        $emergencia->nombres = $request->nombres;
        $emergencia->apellidos = $request->apellidos;
        $emergencia->identidad = $request->identidad;
        $emergencia->edad = $request->edad;
        $emergencia->sexo = $request->sexo;
        $emergencia->telefono = $request->telefono;
    } else {
        $emergencia->codigo_temporal = 'TEMP-' . time();
        if ($request->hasFile('foto')) {
            $emergencia->foto = $request->file('foto')->store('emergencias', 'public');
        }
    }

    $emergencia->save();

    return redirect()->route('emergencias.index')
                     ->with('success', 'Emergencia registrada correctamente.');
}
public function index(Request $request)
{
    try {
        $query = $request->input('search', '');
        $fechaInicio = $request->input('fecha_desde');
        $fechaFin = $request->input('fecha_hasta');
        $documentado = $request->input('documentado'); // <-- NUEVO
        $perPage = 3;

        $emergenciasQuery = Emergencia::orderBy('fecha', 'desc')->orderBy('hora', 'desc');

        // Filtrar por nombres/apellidos
        if ($query) {
            $emergenciasQuery->where(function($q) use ($query) {
                $q->where('nombres', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%");
            });
        }

        // Filtrar por fechas
        if ($fechaInicio) $emergenciasQuery->where('fecha', '>=', $fechaInicio);
        if ($fechaFin) $emergenciasQuery->where('fecha', '<=', $fechaFin);

        // Filtrar por documentación
        if ($documentado !== null && $documentado !== '') {
            $emergenciasQuery->where('documentado', $documentado);
        }

        // Determinar si hay algún filtro activo
        $isSearch = $query || $fechaInicio || $fechaFin || $documentado !== null && $documentado !== '' ? true : false;

        // Obtener resultados
        if ($isSearch) {
            $emergencias = $emergenciasQuery->get();
        } else {
            $emergencias = $emergenciasQuery->paginate($perPage);
        }

        $totalFiltrado = $emergenciasQuery->count();

        // Respuesta AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('emergencias.tabla', compact('emergencias', 'isSearch'))->render(),
                'pagination' => $isSearch ? '' : $emergencias->links('pagination::bootstrap-5')->render(),
                'totalFiltrado' => $totalFiltrado,
                'all' => $totalFiltrado,
            ]);
        }

        return view('emergencias.index', compact('emergencias', 'isSearch', 'totalFiltrado'));

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        abort(500, $e->getMessage());
    }
}

}
