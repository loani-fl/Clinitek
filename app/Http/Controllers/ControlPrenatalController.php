<?php

namespace App\Http\Controllers;

use App\Models\ControlPrenatal;
use App\Models\Paciente;
use Illuminate\Http\Request;

class ControlPrenatalController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search', '');
        $fechaInicio = $request->input('fecha_desde');
        $fechaFin = $request->input('fecha_hasta');
        $perPage = 1;

    $totalControles = ControlPrenatal::count();

    $controlesQuery = ControlPrenatal::with('paciente')
        ->orderBy('fecha_control', 'desc');

        if ($query) {
            $controlesQuery->whereHas('paciente', function($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%");
            });
        }

        if ($fechaInicio) $controlesQuery->where('fecha_control', '>=', $fechaInicio);
        if ($fechaFin) $controlesQuery->where('fecha_control', '<=', $fechaFin);

        $isSearch = ($query || $fechaInicio || $fechaFin);

        $totalFiltrado = $controlesQuery->count();
        $controles = $controlesQuery->paginate($perPage);

        $controles->appends([
            'search' => $query,
            'fecha_desde' => $fechaInicio,
            'fecha_hasta' => $fechaFin
        ]);

        if ($request->ajax()) {
            $currentPage = $controles->currentPage();
            $lastPage = max($controles->lastPage(), 1);

            $customPagination = view('controlPrenatal.custom-pagination', [
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'hasMorePages' => $controles->hasMorePages(),
                'onFirstPage' => $controles->onFirstPage(),
                'from' => $controles->firstItem() ?? 0,
                'to' => $controles->lastItem() ?? 0,
                'total' => $controles->total(),
            ])->render();

            return response()->json([
                'html' => view('controlPrenatal.tabla', compact('controles', 'isSearch'))->render(),
                'pagination' => $customPagination,
                'total' => $totalFiltrado,
                'all' => $totalControles,
            ]);
        }

        return view('controlPrenatal.index', compact('controles', 'isSearch', 'totalControles'));
    }

    public function create()
    {
        $pacientes = Paciente::where('genero', 'Femenino')
            ->orderBy('nombre')
            ->orderBy('apellidos')
            ->get();
        return view('controlPrenatal.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        // Validaciones completas
        $validated = $request->validate([
            // Paciente (nuevo o existente)
            'paciente_existente' => [
                'nullable',
                'exists:pacientes,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $paciente = \App\Models\Paciente::find($value);
                        if ($paciente && $paciente->genero !== 'Femenino') {
                            $fail('Solo se pueden registrar controles prenatales para pacientes de género femenino.');
                        }
                    }
                }
            ],
            'nombre' => [
                'required_if:paciente_existente,null',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'apellidos' => [
                'required_if:paciente_existente,null',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'identidad' => [
                'required_if:paciente_existente,null',
                'string',
                'size:13',
                'regex:/^[0-9]{13}$/',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->paciente_existente && \App\Models\Paciente::where('identidad', $value)->exists()) {
                        $fail('Esta identidad ya está registrada en otro paciente.');
                    }
                },
            ],
            'fecha_nacimiento' => 'required_if:paciente_existente,null|date|before:today',
            'direccion' => 'required_if:paciente_existente,null|string|max:300',
            'telefono' => [
                'required_if:paciente_existente,null',
                'string',
                'size:8',
                'regex:/^[2389][0-9]{7}$/'
            ],
            'correo' => 'nullable|email|max:50',
            'tipo_sangre' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'genero' => 'required_if:paciente_existente,null|string|in:Femenino',
            'padecimientos' => 'nullable|string|max:200',
            'medicamentos' => 'nullable|string|max:200',
            'historial_clinico' => 'nullable|string|max:200',
            'alergias' => 'nullable|string|max:200',
            'historial_quirurgico' => 'nullable|string|max:200',
            
            // Datos obstétricos (OBLIGATORIOS)
            'fecha_ultima_menstruacion' => 'required|date|before_or_equal:today',
            'fecha_probable_parto' => [
                'required',
                'date',
                'after:fecha_ultima_menstruacion'
            ],
            'semanas_gestacion' => 'required|integer|min:0|max:42',
            'numero_partos' => 'required|integer|min:0|max:20',
            'numero_abortos' => 'required|integer|min:0|max:20',
            'tipo_partos_anteriores' => 'nullable|string|max:255',
            'complicaciones_previas' => 'nullable|string|max:500',
            
            // Datos del control prenatal actual (OBLIGATORIOS)
            'fecha_control' => 'required|date|before_or_equal:today',
            'presion_arterial' => [
                'required',
                'string',
                'regex:/^[0-9]{2,3}\/[0-9]{2,3}$/'
            ],
            'frecuencia_cardiaca_materna' => 'required|integer|min:40|max:200',
            'temperatura' => 'required|numeric|min:35|max:42',
            'peso_actual' => 'required|numeric|min:30|max:200',
            'edema' => 'required|in:ninguno,leve,moderado,severo',
            
            // Datos opcionales con límite de caracteres
            'altura_uterina' => 'nullable|numeric|min:0|max:50',
            'latidos_fetales' => 'nullable|integer|min:100|max:180',
            'movimientos_fetales' => 'nullable|string|max:100',
            'presentacion_fetal' => 'nullable|in:cefalica,podalica,transversa,no_determinada',
            'resultados_examenes' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string|max:500',
            
            // Tratamientos y recomendaciones (OPCIONALES)
            'suplementos' => 'nullable|string|max:300',
            'vacunas_aplicadas' => 'nullable|string|max:300',
            'indicaciones_medicas' => 'nullable|string|max:500',
            'fecha_proxima_cita' => 'nullable|date|after:fecha_control',
        ], [
            // Mensajes personalizados para paciente
            'nombre.required_if' => 'El nombre es obligatorio cuando no se selecciona una paciente existente.',
            'nombre.max' => 'El nombre no debe exceder 50 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'apellidos.required_if' => 'Los apellidos son obligatorios cuando no se selecciona una paciente existente.',
            'apellidos.max' => 'Los apellidos no deben exceder 50 caracteres.',
            'apellidos.regex' => 'Los apellidos solo deben contener letras y espacios.',
            'identidad.required_if' => 'La identidad es obligatoria cuando no se selecciona una paciente existente.',
            'identidad.size' => 'La identidad debe tener exactamente 13 dígitos.',
            'identidad.regex' => 'La identidad solo debe contener números.',
            'fecha_nacimiento.required_if' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'direccion.required_if' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no debe exceder 300 caracteres.',
            'telefono.required_if' => 'El teléfono es obligatorio.',
            'telefono.size' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9 y tener 8 dígitos en total.',
            'correo.email' => 'El correo debe ser una dirección válida.',
            'correo.max' => 'El correo no debe exceder 50 caracteres.',
            'tipo_sangre.in' => 'El tipo de sangre seleccionado no es válido.',
            'genero.required_if' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser Femenino para controles prenatales.',
            'padecimientos.max' => 'Los padecimientos no deben exceder 200 caracteres.',
            'medicamentos.max' => 'Los medicamentos no deben exceder 200 caracteres.',
            'historial_clinico.max' => 'El historial clínico no debe exceder 200 caracteres.',
            'alergias.max' => 'Las alergias no deben exceder 200 caracteres.',
            'historial_quirurgico.max' => 'El historial quirúrgico no debe exceder 200 caracteres.',
            
            // Mensajes para datos obstétricos
            'fecha_ultima_menstruacion.required' => 'La fecha de última menstruación es obligatoria.',
            'fecha_ultima_menstruacion.before_or_equal' => 'La fecha de última menstruación no puede ser futura.',
            'fecha_probable_parto.required' => 'La fecha probable de parto es obligatoria.',
            'fecha_probable_parto.after' => 'La fecha probable de parto debe ser posterior a la fecha de última menstruación.',
            'semanas_gestacion.required' => 'Las semanas de gestación son obligatorias.',
            'semanas_gestacion.min' => 'Las semanas de gestación no pueden ser negativas.',
            'semanas_gestacion.max' => 'Las semanas de gestación no pueden exceder 42.',
            'numero_partos.required' => 'El número de partos es obligatorio.',
            'numero_partos.min' => 'El número de partos no puede ser negativo.',
            'numero_partos.max' => 'El número de partos no puede exceder 20.',
            'numero_abortos.required' => 'El número de abortos es obligatorio.',
            'numero_abortos.min' => 'El número de abortos no puede ser negativo.',
            'numero_abortos.max' => 'El número de abortos no puede exceder 20.',
            'tipo_partos_anteriores.max' => 'El tipo de partos anteriores no debe exceder 255 caracteres.',
            'complicaciones_previas.max' => 'Las complicaciones previas no deben exceder 500 caracteres.',
            
            // Mensajes para control prenatal
            'fecha_control.required' => 'La fecha del control es obligatoria.',
            'fecha_control.before_or_equal' => 'La fecha del control no puede ser futura.',
            'presion_arterial.required' => 'La presión arterial es obligatoria.',
            'presion_arterial.regex' => 'La presión arterial debe tener el formato correcto (ejemplo: 120/80).',
            'frecuencia_cardiaca_materna.required' => 'La frecuencia cardíaca materna es obligatoria.',
            'frecuencia_cardiaca_materna.min' => 'La frecuencia cardíaca materna debe ser al menos 40 lpm.',
            'frecuencia_cardiaca_materna.max' => 'La frecuencia cardíaca materna no puede exceder 200 lpm.',
            'temperatura.required' => 'La temperatura es obligatoria.',
            'temperatura.min' => 'La temperatura debe ser al menos 35°C.',
            'temperatura.max' => 'La temperatura no puede exceder 42°C.',
            'peso_actual.required' => 'El peso actual es obligatorio.',
            'peso_actual.min' => 'El peso debe ser al menos 30 kg.',
            'peso_actual.max' => 'El peso no puede exceder 200 kg.',
            'altura_uterina.min' => 'La altura uterina no puede ser negativa.',
            'altura_uterina.max' => 'La altura uterina no puede exceder 50 cm.',
            'latidos_fetales.min' => 'Los latidos fetales deben ser al menos 100 lpm.',
            'latidos_fetales.max' => 'Los latidos fetales no pueden exceder 180 lpm.',
            'movimientos_fetales.max' => 'Los movimientos fetales no deben exceder 100 caracteres.',
            'edema.required' => 'El nivel de edema es obligatorio.',
            'edema.in' => 'El nivel de edema seleccionado no es válido.',
            'presentacion_fetal.in' => 'La presentación fetal seleccionada no es válida.',
            'resultados_examenes.max' => 'Los resultados de exámenes no deben exceder 500 caracteres.',
            'observaciones.max' => 'Las observaciones no deben exceder 500 caracteres.',
            'suplementos.max' => 'Los suplementos no deben exceder 300 caracteres.',
            'vacunas_aplicadas.max' => 'Las vacunas aplicadas no deben exceder 300 caracteres.',
            'indicaciones_medicas.max' => 'Las indicaciones médicas no deben exceder 500 caracteres.',
            'fecha_proxima_cita.after' => 'La fecha de próxima cita debe ser posterior a la fecha del control.',
        ]);

        // Si no se seleccionó paciente existente, crear uno nuevo
        if (!$request->paciente_existente) {
            $paciente = Paciente::create([
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'identidad' => $validated['identidad'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
                'correo' => $validated['correo'] ?? null,
                'tipo_sangre' => $validated['tipo_sangre'] ?? null,
                'genero' => 'Femenino',
                'padecimientos' => $validated['padecimientos'] ?? null,
                'medicamentos' => $validated['medicamentos'] ?? null,
                'historial_clinico' => $validated['historial_clinico'] ?? null,
                'alergias' => $validated['alergias'] ?? null,
                'historial_quirurgico' => $validated['historial_quirurgico'] ?? null,
            ]);
            $paciente_id = $paciente->id;
        } else {
            $paciente_id = $request->paciente_existente;
        }

        // Calcular numero_gestaciones basado en partos y abortos
        $numero_gestaciones = $validated['numero_partos'] + $validated['numero_abortos'] + 1;
        $numero_hijos_vivos = $validated['numero_partos'];

        // Crear control prenatal
        $datosControl = [
            'paciente_id' => $paciente_id,
            'fecha_ultima_menstruacion' => $validated['fecha_ultima_menstruacion'],
            'fecha_probable_parto' => $validated['fecha_probable_parto'],
            'semanas_gestacion' => $validated['semanas_gestacion'],
            'numero_gestaciones' => $numero_gestaciones,
            'numero_partos' => $validated['numero_partos'],
            'numero_abortos' => $validated['numero_abortos'],
            'numero_hijos_vivos' => $numero_hijos_vivos,
            'tipo_partos_anteriores' => $validated['tipo_partos_anteriores'],
            'complicaciones_previas' => $validated['complicaciones_previas'],
            'fecha_control' => $validated['fecha_control'],
            'presion_arterial' => $validated['presion_arterial'],
            'frecuencia_cardiaca_materna' => $validated['frecuencia_cardiaca_materna'],
            'temperatura' => $validated['temperatura'],
            'peso_actual' => $validated['peso_actual'],
            'altura_uterina' => $validated['altura_uterina'],
            'latidos_fetales' => $validated['latidos_fetales'],
            'movimientos_fetales' => $validated['movimientos_fetales'],
            'edema' => $validated['edema'],
            'presentacion_fetal' => $validated['presentacion_fetal'],
            'resultados_examenes' => $validated['resultados_examenes'],
            'observaciones' => $validated['observaciones'],
            'suplementos' => $validated['suplementos'],
            'vacunas_aplicadas' => $validated['vacunas_aplicadas'],
            'indicaciones_medicas' => $validated['indicaciones_medicas'],
            'fecha_proxima_cita' => $validated['fecha_proxima_cita'],
        ];

        ControlPrenatal::create($datosControl);

        return redirect()->route('controles-prenatales.index')
        ->with('success', 'Control prenatal registrado con éxito');
    }

    public function show(ControlPrenatal $controlPrenatal)
    {
        $controlPrenatal->load('paciente');
        return view('controlPrenatal.show', compact('controlPrenatal'));
    }

    public function edit(ControlPrenatal $controlPrenatal)
    {
        $pacientes = Paciente::where('genero', 'Femenino')
            ->orderBy('nombre')
            ->orderBy('apellidos')
            ->get();
        return view('controlPrenatal.edit', compact('controlPrenatal', 'pacientes'));
    }

    public function update(Request $request, ControlPrenatal $controlPrenatal)
    {
        $validated = $request->validate([
            // Datos obstétricos
            'fecha_ultima_menstruacion' => 'required|date|before_or_equal:today',
            'fecha_probable_parto' => 'required|date|after:fecha_ultima_menstruacion',
            'semanas_gestacion' => 'required|integer|min:0|max:42',
            'numero_partos' => 'required|integer|min:0|max:20',
            'numero_abortos' => 'required|integer|min:0|max:20',
            'tipo_partos_anteriores' => 'nullable|string|max:255',
            'complicaciones_previas' => 'nullable|string|max:500',
            
            // Datos del control prenatal actual
            'fecha_control' => 'required|date|before_or_equal:today',
            'presion_arterial' => 'required|string|regex:/^[0-9]{2,3}\/[0-9]{2,3}$/',
            'frecuencia_cardiaca_materna' => 'required|integer|min:40|max:200',
            'temperatura' => 'required|numeric|min:35|max:42',
            'peso_actual' => 'required|numeric|min:30|max:200',
            'altura_uterina' => 'nullable|numeric|min:0|max:50',
            'latidos_fetales' => 'nullable|integer|min:100|max:180',
            'movimientos_fetales' => 'nullable|string|max:100',
            'edema' => 'required|in:ninguno,leve,moderado,severo',
            'presentacion_fetal' => 'nullable|in:cefalica,podalica,transversa,no_determinada',
            'resultados_examenes' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string|max:500',
            
            // Tratamientos y recomendaciones
            'suplementos' => 'nullable|string|max:300',
            'vacunas_aplicadas' => 'nullable|string|max:300',
            'indicaciones_medicas' => 'nullable|string|max:500',
            'fecha_proxima_cita' => 'nullable|date|after:fecha_control',
        ]);

        // Recalcular numero_gestaciones y numero_hijos_vivos
        $validated['numero_gestaciones'] = $validated['numero_partos'] + $validated['numero_abortos'] + 1;
        $validated['numero_hijos_vivos'] = $validated['numero_partos'];

        $controlPrenatal->update($validated);

        return redirect()->route('controles-prenatales.show', $controlPrenatal)
            ->with('success', 'Control prenatal actualizado exitosamente.');
    }

    public function destroy(ControlPrenatal $controlPrenatal)
    {
        $controlPrenatal->delete();

        return redirect()->route('controles-prenatales.index')
            ->with('success', 'Control prenatal eliminado exitosamente.');
    }
}