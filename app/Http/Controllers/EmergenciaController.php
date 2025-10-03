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
}
public function store(Request $request)
{
    $anioActual = date('Y');

    // Función para validar año de identidad
    $validarAnioIdentidad = fn($identidad) => intval(substr($identidad, 4, 4)) >= 1930 && intval(substr($identidad, 4, 4)) <= $anioActual;

    // Reglas base
    $rules = [
        'documentado' => 'required|boolean',
        'motivo' => 'required|string|max:300',
        'pa' => 'required|string|max:7',
        'fc' => 'required|integer|min:1|max:300',
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
        // Campos indocumentado
        $rules = array_merge($rules, [
            'foto' => 'required|file|mimes:jpeg,jpg,png|max:2048',
        ]);
    }

    $request->validate($rules, [
        'motivo.required' => 'El motivo de la emergencia es obligatorio.',
        'pa.required' => 'La presión arterial es obligatoria.',
        'fc.required' => 'La frecuencia cardíaca es obligatoria.',
        'temp.required' => 'La temperatura es obligatoria.',
        'nombres.required' => 'El nombre es obligatorio.',
        'apellidos.required' => 'Los apellidos son obligatorios.',
        'identidad.required' => 'La identidad es obligatoria.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'edad.required' => 'La edad es obligatoria.',
        'edad.max' => 'La edad no puede ser mayor a 105 años.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'direccion.required' => 'La dirección es obligatoria.',
        'genero.required' => 'El género es obligatorio.',
        'foto.required' => 'La foto es obligatoria para pacientes indocumentados.',
    ]);

    // Validar coherencia entre edad y fecha de nacimiento
    if ($request->documentado && $request->filled('fecha_nacimiento')) {
        $fechaNacimiento = new \DateTime($request->fecha_nacimiento);
        $hoy = new \DateTime();
        $edadCalculada = $hoy->diff($fechaNacimiento)->y;

        if ($edadCalculada != $request->edad) {
            return redirect()->back()
                ->withErrors(['edad' => "La edad ingresada ($request->edad) no coincide con la fecha de nacimiento. Edad real: $edadCalculada"])
                ->withInput();
        }
    }

    // Validación manual de identidad
    if ($request->documentado && !$validarAnioIdentidad($request->identidad)) {
        return redirect()->back()
            ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
            ->withInput();
    }

    // Guardar datos de emergencia
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
        $emergencia = Emergencia::findOrFail($id);
        return view('emergencias.show', compact('emergencia'));
    }
}