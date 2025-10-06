<?php

namespace App\Http\Controllers;

use App\Models\Emergencia;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmergenciaController extends Controller
{
    public function create()
    {
        return view('emergencias.create');
    }

  public function index(Request $request)
{
    $query = $request->input('search', '');
    $fechaInicio = $request->input('fecha_desde');
    $fechaFin = $request->input('fecha_hasta');
    $documentado = $request->input('documentado');
    $perPage = 3; // Mantén tu valor original

    $totalEmergencias = Emergencia::count();

    $emergenciasQuery = Emergencia::with('paciente')
        ->orderBy('fecha', 'desc')
        ->orderBy('hora', 'desc');

    if ($query) {
        $emergenciasQuery->whereHas('paciente', function($q) use ($query) {
            $q->where('nombre', 'like', "%$query%")
              ->orWhere('apellidos', 'like', "%$query%");
        });
    }

    if ($fechaInicio) $emergenciasQuery->where('fecha', '>=', $fechaInicio);
    if ($fechaFin) $emergenciasQuery->where('fecha', '<=', $fechaFin);

    if ($documentado !== null && $documentado !== '') {
        $emergenciasQuery->where('documentado', $documentado);
    }

    $isSearch = ($query || $fechaInicio || $fechaFin || ($documentado !== null && $documentado !== ''));
    
    $totalFiltrado = $emergenciasQuery->count();
    $emergencias = $emergenciasQuery->paginate($perPage);
    
    $emergencias->appends([
        'search' => $query,
        'fecha_desde' => $fechaInicio,
        'fecha_hasta' => $fechaFin,
        'documentado' => $documentado
    ]);

    if ($request->ajax()) {
        // Crear paginación personalizada que siempre se muestra
        $currentPage = $emergencias->currentPage();
        $lastPage = max($emergencias->lastPage(), 1); // Mínimo 1 página
        
        $customPagination = view('emergencias.custom-pagination', [
            'currentPage' => $currentPage,
            'lastPage' => $lastPage,
            'hasMorePages' => $emergencias->hasMorePages(),
            'onFirstPage' => $emergencias->onFirstPage(),
            'from' => $emergencias->firstItem() ?? 0,  // ← AGREGAR ESTO
                'to' => $emergencias->lastItem() ?? 0,      // ← AGREGAR ESTO
                'total' => $emergencias->total(), 
        ])->render();
        
        return response()->json([
            'html' => view('emergencias.tabla', compact('emergencias', 'isSearch'))->render(),
            'pagination' => $customPagination,
            'total' => $totalFiltrado,
            'totalFiltrado' => $totalFiltrado,
            'all' => $totalEmergencias,
        ]);
    }

    return view('emergencias.index', compact('emergencias', 'isSearch'));
}public function store(Request $request)
{
    $anioActual = date('Y');

    // Función para validar año de identidad
    $validarAnioIdentidad = fn($identidad) => intval(substr($identidad, 4, 4)) >= 1930 && intval(substr($identidad, 4, 4)) <= $anioActual;

    // CONVERTIR FECHA ANTES DE VALIDAR
    if($request->filled('fecha_nacimiento') && $request->documentado == 1) {
        $fechaPartes = explode('/', $request->fecha_nacimiento);
        if(count($fechaPartes) == 3) {
            $fechaConvertida = $fechaPartes[2] . '-' . $fechaPartes[1] . '-' . $fechaPartes[0];
            $request->merge(['fecha_nacimiento' => $fechaConvertida]);
        }
    }

    // Reglas base
    $rules = [
        'documentado' => 'required|boolean',
        'motivo' => 'required|string|max:300',
        'pa' => ['required', 'string', 'regex:/^\d{2,3}\/\d{2,3}$/'],
        'fc' => 'required|integer|min:20|max:250',
        'temp' => 'required|numeric|between:30,45',
    ];

    // Campos de paciente documentado
    if ($request->documentado) {
        $rules = array_merge($rules, [
            'nombres' => 'required|regex:/^[\pL\s]+$/u|max:50',
            'apellidos' => 'required|regex:/^[\pL\s]+$/u|max:50',
            'identidad' => 'required|digits:13',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
            'edad' => 'required|integer|min:0|max:105',
            'telefono' => 'required|digits:8|regex:/^[2389][0-9]{7}$/',
            'direccion' => 'required|string|max:300',
            'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'genero' => 'required|in:Femenino,Masculino,Otro',
        ]);
    } else {
        // Campos indocumentado - SIN edad
        $rules = array_merge($rules, [
    'foto' => [
        'required',
        'file',
        'mimetypes:image/jpeg,image/png',
        'mimes:jpg,jpeg,png',
        'max:2048', // 2 MB máximo
    ],
]);
    }

    // Validación adicional de presión arterial
    if ($request->filled('pa')) {
        $partes = explode('/', $request->pa);
        if (count($partes) === 2) {
            $sistolica = (int)$partes[0];
            $diastolica = (int)$partes[1];
            
            if ($sistolica < 40 || $sistolica > 250) {
                return redirect()->back()
                    ->withErrors(['pa' => 'La presión sistólica debe estar entre 40 y 250 mmHg.'])
                    ->withInput();
            }
            
            if ($diastolica < 20 || $diastolica > 150) {
                return redirect()->back()
                    ->withErrors(['pa' => 'La presión diastólica debe estar entre 20 y 150 mmHg.'])
                    ->withInput();
            }
            
            if ($sistolica <= $diastolica) {
                return redirect()->back()
                    ->withErrors(['pa' => 'La presión sistólica debe ser mayor que la diastólica.'])
                    ->withInput();
            }
        }
    }

    $request->validate($rules, [
        'motivo.required' => 'El motivo de la emergencia es obligatorio.',
        'pa.required' => 'La presión arterial es obligatoria.',
        'pa.regex' => 'La presión arterial debe tener el formato: 120/80',
        'fc.required' => 'La frecuencia cardíaca es obligatoria.',
        'fc.min' => 'La frecuencia cardíaca debe ser al menos 20 lpm.',
        'fc.max' => 'La frecuencia cardíaca no puede superar 250 lpm.',
        'temp.required' => 'La temperatura es obligatoria.',
        'temp.between' => 'La temperatura debe estar entre 30 y 45°C.',
        'nombres.required' => 'El nombre es obligatorio.',
        'apellidos.required' => 'Los apellidos son obligatorios.',
        'identidad.required' => 'La identidad es obligatoria.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'edad.required' => 'La edad es obligatoria.',
        'edad.max' => 'La edad no puede ser mayor a 105 años.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.regex' => 'El número de teléfono debe comenzar con 2, 3, 8 o 9.',
        'direccion.required' => 'La dirección es obligatoria.',
        'genero.required' => 'El género es obligatorio.',
        'foto.required' => 'La foto es obligatoria para pacientes indocumentados.',
        'foto.mimes' => 'La foto debe ser un archivo con formato JPG, JPEG o PNG.',
'foto.mimetypes' => 'El archivo debe ser una imagen válida (JPG, JPEG o PNG).',
'foto.max' => 'La foto no debe superar los 2 MB.',

    ]);

    // Validación manual de identidad
    if ($request->documentado && !$validarAnioIdentidad($request->identidad)) {
        return redirect()->back()
            ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
            ->withInput();
    }

    // Resto del código igual...
    $dataEmergencia = [
        'documentado' => $request->documentado,
        'motivo' => $request->motivo,
        'pa' => $request->pa,
        'fc' => $request->fc,
        'temp' => $request->temp,
        'fecha' => $request->fecha ?: now()->format('Y-m-d'),
        'hora' => $request->hora ?: now()->format('H:i:s'),
    ];

    if ($request->documentado) {
        $paciente = Paciente::firstOrCreate(
            ['identidad' => $request->identidad],
            [
                'nombre' => $request->nombres,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'edad' => $request->edad,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'tipo_sangre' => $request->tipo_sangre ?? 'N/A',
                'genero' => $request->genero,
                'correo' => 'emergencia@temp.com',
                'padecimientos' => 'N/A',
                'medicamentos' => 'N/A',
                'historial_clinico' => 'N/A',
                'alergias' => 'N/A',
            ]
        );

        $dataEmergencia['paciente_id'] = $paciente->id;
        $dataEmergencia['foto'] = null;
    } else {
        $dataEmergencia['paciente_id'] = null;
        if ($request->hasFile('foto')) {
            $dataEmergencia['foto'] = $request->file('foto')->store('fotos_emergencias', 'public');
        }
    }

    Emergencia::create($dataEmergencia);

    return redirect()->route('emergencias.index')->with('success', 'Emergencia registrada correctamente.');
}

   public function show($id)
{
    // Buscar la emergencia por ID
    $emergencia = \App\Models\Emergencia::findOrFail($id);

    // Obtener el paciente relacionado con la emergencia
    $paciente = $emergencia->paciente; // usa la relación belongsTo en el modelo

    // Obtener el historial de emergencias del mismo paciente
    $historial = \App\Models\Emergencia::where('paciente_id', $emergencia->paciente_id)
        ->where('id', '!=', $emergencia->id)
        ->orderBy('fecha', 'desc')
        ->get();

    // Pasar todos los datos a la vista
    return view('emergencias.show', compact('emergencia', 'paciente', 'historial'));
}

}