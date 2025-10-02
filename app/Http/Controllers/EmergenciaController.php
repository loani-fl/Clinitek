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
        $perPage = 3;

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
        $emergencias = $isSearch ? $emergenciasQuery->get() : $emergenciasQuery->paginate($perPage);
        $totalFiltrado = $emergenciasQuery->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('emergencias.tabla', compact('emergencias', 'isSearch'))->render(),
                'pagination' => $isSearch ? '' : $emergencias->links('pagination::bootstrap-5')->render(),
                'totalFiltrado' => $totalFiltrado,
                'all' => $totalFiltrado,
            ]);
        }

        return view('emergencias.index', compact('emergencias', 'isSearch', 'totalFiltrado'));
    }

    public function store(Request $request)
    {
        $anioActual = date('Y');

        // Función para validar año en la identidad
        $validarAnioIdentidad = function($identidad) use ($anioActual) {
            $anio = intval(substr($identidad, 4, 4));
            return $anio >= 1930 && $anio <= $anioActual;
        };

        // PRIMERO: Validar campos comunes (motivo y signos vitales)
        $request->validate([
            'motivo' => ['required', 'string', 'max:300'],
            'pa' => ['required', 'string', 'max:7'],
            'fc' => ['required', 'integer', 'min:1', 'max:300'],
            'temp' => ['required', 'numeric', 'between:30,45'],
        ], [
            'motivo.required' => 'El motivo de la emergencia es obligatorio.',
            'motivo.max' => 'El motivo no puede superar los 300 caracteres.',
            'pa.required' => 'La presión arterial es obligatoria.',
            'pa.max' => 'La presión arterial no puede superar los 7 caracteres.',
            'fc.required' => 'La frecuencia cardíaca es obligatoria.',
            'fc.integer' => 'La frecuencia cardíaca debe ser un número válido.',
            'fc.min' => 'La frecuencia cardíaca debe ser mayor a 0.',
            'fc.max' => 'La frecuencia cardíaca no puede superar 300.',
            'temp.required' => 'La temperatura es obligatoria.',
            'temp.numeric' => 'La temperatura debe ser un número válido.',
            'temp.between' => 'La temperatura debe estar entre 30 y 45°C.',
        ]);

        $dataEmergencia = [];

        // SEGUNDO: Validaciones específicas según tipo de paciente
        if ($request->input('documentado') == 1) {
            // Validaciones para paciente documentado
            $request->validate([
                'nombres' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
                'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
                'identidad' => ['required', 'digits:13'],
                'fecha_nacimiento' => ['required', 'date', 'after_or_equal:' . Carbon::now()->subYears(60)->format('Y-m-d')],
                'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/'],
                'direccion' => ['required', 'string', 'max:300'],
                'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
                'genero' => ['required', 'in:Femenino,Masculino,Otro'],
            ], [
                'nombres.required' => 'El nombre es obligatorio.',
                'nombres.regex' => 'El nombre solo puede contener letras y espacios.',
                'nombres.max' => 'El nombre no puede superar los 50 caracteres.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
                'apellidos.max' => 'Los apellidos no pueden superar los 50 caracteres.',
                'identidad.required' => 'La identidad es obligatoria.',
                'identidad.digits' => 'La identidad debe tener exactamente 13 dígitos.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'Debe ser una fecha válida.',
                'fecha_nacimiento.after_or_equal' => 'El paciente no puede tener más de 60 años.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
                'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
                'direccion.required' => 'La dirección es obligatoria.',
                'direccion.max' => 'La dirección no puede superar los 300 caracteres.',
                'tipo_sangre.in' => 'El tipo de sangre debe ser válido.',
                'genero.required' => 'El género es obligatorio.',
                'genero.in' => 'El género debe ser Femenino, Masculino u Otro.',
            ]);

            if (!$validarAnioIdentidad($request->identidad)) {
                return redirect()->back()
                    ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
                    ->withInput();
            }

            // Buscar si el paciente ya existe
            $paciente = Paciente::where('identidad', $request->identidad)->first();

            if (!$paciente) {
                // Si no existe, crear nuevo paciente
                $paciente = Paciente::create([
                    'nombre' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'identidad' => $request->identidad,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'telefono' => $request->telefono,
                    'tipo_sangre' => $request->tipo_sangre,
                    'genero' => $request->genero,
                    'direccion' => $request->direccion,
                    'correo' => 'emergencia@temp.com', // Valor temporal
                    'padecimientos' => 'N/A',
                    'medicamentos' => 'N/A',
                    'historial_clinico' => 'N/A',
                    'alergias' => 'N/A',
                ]);
            }

            $dataEmergencia['paciente_id'] = $paciente->id;
            
        } else {
            // Validaciones para paciente indocumentado
            $dataEmergencia['paciente_id'] = null;

            $request->validate([
                'foto' => ['required', 'file', 'mimes:jpeg,jpg,png', 'max:2048'],
            ], [
                'foto.required' => 'La foto es obligatoria para pacientes indocumentados.',
                'foto.file' => 'El archivo debe ser una imagen válida.',
                'foto.mimes' => 'La foto debe ser un archivo de tipo: png, jpg o jpeg.',
                'foto.max' => 'La foto no puede superar los 2MB.',
            ]);

            if ($request->hasFile('foto')) {
                $rutaFoto = $request->file('foto')->store('fotos_emergencias', 'public');
                $dataEmergencia['foto'] = $rutaFoto;
            }
        }

        // Preparar datos de la emergencia
        $dataEmergencia['documentado'] = $request->documentado;
        $dataEmergencia['motivo'] = $request->motivo;
        $dataEmergencia['pa'] = $request->pa;
        $dataEmergencia['fc'] = $request->fc;
        $dataEmergencia['temp'] = $request->temp;
        $dataEmergencia['fecha'] = $request->fecha ?: now()->format('Y-m-d');
        $dataEmergencia['hora'] = $request->hora ?: now()->format('H:i:s');

        // Crear el registro de emergencia
        Emergencia::create($dataEmergencia);

        return redirect()->route('emergencias.index')->with('success', 'Emergencia registrada correctamente.');
    }

    public function show($id)
    {
        $emergencia = Emergencia::findOrFail($id);
        return view('emergencias.show', compact('emergencia'));
    }
}