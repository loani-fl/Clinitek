<?php

namespace App\Http\Controllers;
use App\Models\Medico;
use App\Models\RayosxOrderExamen;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\RayosxOrder;
use Illuminate\Support\Facades\Storage;
use App\Models\PacienteRayosX;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class OrdenRayosXController extends Controller
{
    /**
     * Mostrar listado paginado.
     */
public function index(Request $request)
{
    try {
        $query = $request->input('search', '');
        $ordenesQuery = RayosxOrder::with(['examenes', 'pacienteClinica', 'pacienteRayosX', 'diagnostico.paciente'])
            ->orderBy('fecha', 'desc');

        if ($query) {
            $ordenesQuery->where(function ($q) use ($query) {
                $q->where(function($q2) use ($query) {
                    // Para pacientes tipo 'clinica'
                    $q2->where('paciente_tipo', 'clinica')
                       ->whereHas('pacienteClinica', function ($q3) use ($query) {
                            $q3->where('nombre', 'like', "%$query%")
                               ->orWhere('apellidos', 'like', "%$query%");
                       });
                })
                ->orWhere(function($q2) use ($query) {
                    // Para pacientes tipo 'rayosx'
                    $q2->where('paciente_tipo', 'rayosx')
                       ->whereHas('pacienteRayosX', function ($q3) use ($query) {
                            $q3->where('nombre', 'like', "%$query%")
                               ->orWhere('apellidos', 'like', "%$query%");
                       });
                })
                ->orWhere(function($q2) use ($query) {
                    // Para órdenes con paciente directo sin relación
                    $q2->whereNull('paciente_tipo')
                       ->where(function ($q3) use ($query) {
                           $q3->where('nombres', 'like', "%$query%")
                              ->orWhere('apellidos', 'like', "%$query%");
                       });
                });
            });

            $ordenes = $ordenesQuery->take(50)->get();
            $isSearch = true;
        } else {
            $ordenes = $ordenesQuery->paginate(5);
            $isSearch = false;
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('rayosx.partials.tabla', compact('ordenes', 'isSearch'))->render(),
                'pagination' => $isSearch ? '' : $ordenes->links('pagination::bootstrap-5')->render(),
                'total' => $ordenes->count(),
                'all' => RayosxOrder::count(),
            ]);
        }

        return view('rayosx.index', compact('ordenes', 'isSearch'));
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        abort(500, $e->getMessage());
    }
}




    /**
     * Mostrar formulario de creación.
     */
  public function create(Request $request)
    {
        $pacientesClinica = Paciente::orderBy('nombre')->get();
        $pacientesRayosX = PacienteRayosX::orderBy('nombre')->get();
        $diagnosticos = Diagnostico::orderBy('id', 'desc')->get();

        $secciones = [
            'CABEZA' => [
                'craneo_anterior_posterior',
                'craneo_lateral',
                'waters',
                'waters_lateral',
                'conductos_auditivos',
                'cavum',
                'senos_paranasales',
                'silla_turca',
                'huesos_nasales',
                'atm_tm',
                'mastoides',
                'mandibula',
            ],
            'TÓRAX' => [
                'torax_posteroanterior_pa',
                'torax_anteroposterior_ap',
                'torax_lateral',
                'torax_oblicuo',
                'torax_superior',
                'torax_inferior',
                'costillas_superiores',
                'costillas_inferiores',
                'esternon_frontal',
                'esternon_lateral',
            ],
            'ABDOMEN' => [
                'abdomen_simple',
                'abdomen_agudo',
                'abdomen_erecto',
                'abdomen_decubito',
            ],
            'EXTREMIDAD SUPERIOR' => [
                'clavicula_izquierda',
                'clavicula_derecha',
                'hombro_anterior',
                'hombro_lateral',
                'humero_proximal',
                'humero_distal',
                'codo_anterior',
                'codo_lateral',
                'antebrazo',
                'muneca',
                'mano',
            ],
            'EXTREMIDAD INFERIOR' => [
                'cadera_izquierda',
                'cadera_derecha',
                'femur_proximal',
                'femur_distal',
                'rodilla_anterior',
                'rodilla_lateral',
                'tibia',
                'pie',
                'calcaneo',
            ],
            'COLUMNA Y PELVIS' => [
                'columna_cervical_lateral',
                'columna_cervical_anteroposterior',
                'columna_dorsal_lateral',
                'columna_dorsal_anteroposterior',
                'columna_lumbar_lateral',
                'columna_lumbar_anteroposterior',
                'sacro_coxis',
                'pelvis_anterior_posterior',
                'pelvis_oblicua',
                'escoliosis',
            ],
            'ESTUDIOS ESPECIALES' => [
                'arteriograma_simple',
                'arteriograma_contraste',
                'histerosalpingograma_simple',
                'histerosalpingograma_contraste',
                'colecistograma_simple',
                'colecistograma_contraste',
                'fistulograma_simple',
                'fistulograma_contraste',
                'artrograma_simple',
                'artrograma_contraste',
            ],
        ];

        $examenes = [
            'craneo_anterior_posterior' => 'Cráneo Anterior Posterior',
            'craneo_lateral' => 'Cráneo Lateral',
            'waters' => 'Waters',
            'waters_lateral' => 'Waters Lateral',
            'conductos_auditivos' => 'Conductos Auditivos',
            'cavum' => 'Cavum',
            'senos_paranasales' => 'Senos Paranasales',
            'silla_turca' => 'Silla Turca',
            'huesos_nasales' => 'Huesos Nasales',
            'atm_tm' => 'ATM - TM',
            'mastoides' => 'Mastoides',
            'mandibula' => 'Mandíbula',
            'torax_posteroanterior_pa' => 'Tórax PA',
            'torax_anteroposterior_ap' => 'Tórax AP',
            'torax_lateral' => 'Tórax Lateral',
            'torax_oblicuo' => 'Tórax Oblicuo',
            'torax_superior' => 'Tórax Superior',
            'torax_inferior' => 'Tórax Inferior',
            'costillas_superiores' => 'Costillas Superiores',
            'costillas_inferiores' => 'Costillas Inferiores',
            'esternon_frontal' => 'Esternón Frontal',
            'esternon_lateral' => 'Esternón Lateral',
            'abdomen_simple' => 'Abdomen Simple',
            'abdomen_agudo' => 'Abdomen Agudo',
            'abdomen_erecto' => 'Abdomen Ereto',
            'abdomen_decubito' => 'Abdomen Decúbito',
            'clavicula_izquierda' => 'Clavícula Izquierda',
            'clavicula_derecha' => 'Clavícula Derecha',
            'hombro_anterior' => 'Hombro Anterior',
            'hombro_lateral' => 'Hombro Lateral',
            'humero_proximal' => 'Húmero Próximal',
            'humero_distal' => 'Húmero Distal',
            'codo_anterior' => 'Codo Anterior',
            'codo_lateral' => 'Codo Lateral',
            'antebrazo' => 'Antebrazo',
            'muneca' => 'Muñeca',
            'mano' => 'Mano',
            'cadera_izquierda' => 'Cadera Izquierda',
            'cadera_derecha' => 'Cadera Derecha',
            'femur_proximal' => 'Fémur Próximal',
            'femur_distal' => 'Fémur Distal',
            'rodilla_anterior' => 'Rodilla Anterior',
            'rodilla_lateral' => 'Rodilla Lateral',
            'tibia' => 'Tibia',
            'pie' => 'Pie',
            'calcaneo' => 'Calcáneo',
            'columna_cervical_lateral' => 'Cervical Lateral',
            'columna_cervical_anteroposterior' => 'Cervical Anteroposterior',
            'columna_dorsal_lateral' => 'Dorsal Lateral',
            'columna_dorsal_anteroposterior' => 'Dorsal Anteroposterior',
            'columna_lumbar_lateral' => 'Lumbar Lateral',
            'columna_lumbar_anteroposterior' => 'Lumbar Anteroposterior',
            'sacro_coxis' => 'Sacro Coxis',
            'pelvis_anterior_posterior' => 'Pelvis Anterior Posterior',
            'pelvis_oblicua' => 'Pelvis Oblicua',
            'escoliosis' => 'Escoliosis',
            'arteriograma_simple' => 'Arteriograma Simple',
            'arteriograma_contraste' => 'Arteriograma con Contraste',
            'histerosalpingograma_simple' => 'Histerosalpingograma Simple',
            'histerosalpingograma_contraste' => 'Histerosalpingograma con Contraste',
            'colecistograma_simple' => 'Colecistograma Simple',
            'colecistograma_contraste' => 'Colecistograma con Contraste',
            'fistulograma_simple' => 'Fistulograma Simple',
            'fistulograma_contraste' => 'Fistulograma con Contraste',
            'artrograma_simple' => 'Artrograma Simple',
            'artrograma_contraste' => 'Artrograma con Contraste',
        ];

        return view('rayosX.create', [
            'pacientesClinica' => $pacientesClinica,
            'pacientesRayosX' => $pacientesRayosX,
            'diagnosticos' => $diagnosticos,
            'seleccion' => $request->query('seleccion'),
            'examenes' => $examenes,
            'secciones' => $secciones,
            'paciente_tipo' => null,
        ]);
    }

    /**
     * Guardar nueva orden.
     */
    public function store(Request $request)
    {
        $request->validate([
            'seleccion' => ['required', 'string'],
            'fecha' => ['required','date'],
            'examenes' => ['required','array','min:1','max:10'],
            'examenes.*' => ['string'],
            'nombres' => [Rule::requiredIf(fn() => $request->seleccion === 'manual'), 'nullable', 'string', 'max:255'],
            'apellidos' => [Rule::requiredIf(fn() => $request->seleccion === 'manual'), 'nullable', 'string', 'max:255'],
            'identidad' => [Rule::requiredIf(fn() => $request->seleccion === 'manual'), 'nullable', 'digits:13', 'string', 'max:13'],
            'edad' => ['nullable','integer','min:0','max:150'],
            'datos_clinicos' => ['nullable','string'],
        ], [
            'seleccion.required' => 'Debe seleccionar diagnóstico o paciente.',
            'fecha.required' => 'La fecha es obligatoria.',
            'examenes.required' => 'Seleccione al menos un examen.',
            'examenes.max' => 'No puede seleccionar más de 10 exámenes.'
        ]);

        // Log para depurar qué exámenes llegan
        \Log::info('Exámenes recibidos:', $request->examenes);

        $preciosExamenes = [
            'craneo_anterior_posterior' => 120.00,
            'craneo_lateral' => 110.00,
            'waters' => 100.00,
            'waters_lateral' => 100.00,
            'conductos_auditivos' => 80.00,
            'cavum' => 90.00,
            'senos_paranasales' => 85.00,
            'silla_turca' => 95.00,
            'huesos_nasales' => 75.00,
            'atm_tm' => 90.00,
            'mastoides' => 88.00,
            'mandibula' => 85.00,
            'torax_posteroanterior_pa' => 150.00,
            'torax_anteroposterior_ap' => 150.00,
            'torax_lateral' => 140.00,
            'torax_oblicuo' => 130.00,
            'torax_superior' => 120.00,
            'torax_inferior' => 120.00,
            'costillas_superiores' => 110.00,
            'costillas_inferiores' => 110.00,
            'esternon_frontal' => 100.00,
            'esternon_lateral' => 100.00,
            'abdomen_simple' => 130.00,
            'abdomen_agudo' => 150.00,
            'abdomen_erecto' => 140.00,
            'abdomen_decubito' => 140.00,
            'clavicula_izquierda' => 90.00,
            'clavicula_derecha' => 90.00,
            'hombro_anterior' => 100.00,
            'hombro_lateral' => 100.00,
            'humero_proximal' => 110.00,
            'humero_distal' => 110.00,
            'codo_anterior' => 90.00,
            'codo_lateral' => 90.00,
            'antebrazo' => 80.00,
            'muneca' => 80.00,
            'mano' => 80.00,
            'cadera_izquierda' => 120.00,
            'cadera_derecha' => 120.00,
            'femur_proximal' => 130.00,
            'femur_distal' => 130.00,
            'rodilla_anterior' => 110.00,
            'rodilla_lateral' => 110.00,
            'tibia' => 100.00,
            'pie' => 90.00,
            'calcaneo' => 90.00,
            'columna_cervical_lateral' => 100.00,
            'columna_cervical_anteroposterior' => 100.00,
            'columna_dorsal_lateral' => 110.00,
            'columna_dorsal_anteroposterior' => 110.00,
            'columna_lumbar_lateral' => 110.00,
            'columna_lumbar_anteroposterior' => 110.00,
            'sacro_coxis' => 100.00,
            'pelvis_anterior_posterior' => 120.00,
            'pelvis_oblicua' => 120.00,
            'escoliosis' => 100.00,
            'arteriograma_simple' => 250.00,
            'arteriograma_contraste' => 300.00,
            'histerosalpingograma_simple' => 230.00,
            'histerosalpingograma_contraste' => 280.00,
            'colecistograma_simple' => 220.00,
            'colecistograma_contraste' => 270.00,
            'fistulograma_simple' => 210.00,
            'fistulograma_contraste' => 260.00,
            'artrograma_simple' => 200.00,
            'artrograma_contraste' => 250.00,
        ];

        $total = 0;
        foreach ($request->examenes as $codigo) {
            if (isset($preciosExamenes[$codigo])) {
                $total += $preciosExamenes[$codigo];
            } else {
                \Log::warning("Examen no encontrado en precios: $codigo");
            }
        }

        \Log::info('Total calculado para la orden: ' . $total);

        $diagnostico_id = null;
        $paciente_id = null;
        $paciente_tipo = null;

        if (str_starts_with($request->seleccion, 'diagnostico-')) {
            $diagnostico_id = (int) str_replace('diagnostico-', '', $request->seleccion);
        } elseif (str_starts_with($request->seleccion, 'clinica-')) {
            $paciente_id = (int) str_replace('clinica-', '', $request->seleccion);
            $paciente_tipo = 'clinica';
        } elseif (str_starts_with($request->seleccion, 'rayosx-')) {
            $paciente_id = (int) str_replace('rayosx-', '', $request->seleccion);
            $paciente_tipo = 'rayosx';
        } elseif ($request->seleccion === 'manual') {
            // manual, no id
        } else {
            return back()->withInput()->with('error', 'Selección inválida.');
        }

        if ($diagnostico_id && !Diagnostico::find($diagnostico_id)) {
            return back()->withInput()->with('error', 'Diagnóstico no encontrado.');
        }
        if ($paciente_tipo === 'clinica' && !Paciente::find($paciente_id)) {
            return back()->withInput()->with('error', 'Paciente (clínica) no encontrado.');
        }
        if ($paciente_tipo === 'rayosx' && !PacienteRayosX::find($paciente_id)) {
            return back()->withInput()->with('error', 'Paciente (Rayos X) no encontrado.');
        }

        $identidad = $request->identidad ?? null;
        $edad = $request->edad ?? null;
        $nombres = $request->nombres ?? null;
        $apellidos = $request->apellidos ?? null;

        if ($paciente_tipo === 'clinica') {
            $p = Paciente::find($paciente_id);
            $identidad = $p->identidad ?? $identidad;
            $edad = $p->edad ?? $edad;
            $nombres = $p->nombre ?? $nombres;
            $apellidos = $p->apellidos ?? $apellidos;
        } elseif ($paciente_tipo === 'rayosx') {
            $p = PacienteRayosX::find($paciente_id);
            $identidad = $p->identidad ?? $identidad;
            $edad = $p->edad ?? $edad;
            $nombres = $p->nombre ?? $nombres;
            $apellidos = $p->apellidos ?? $apellidos;
        }

        DB::beginTransaction();
        try {
            $orden = RayosxOrder::create([
                'diagnostico_id' => $diagnostico_id,
                'paciente_id' => $paciente_id,
                'paciente_tipo' => $paciente_tipo,
                'fecha' => $request->fecha,
                'edad' => $edad,
                'identidad' => $identidad,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'datos_clinicos' => $request->datos_clinicos,
                'estado' => 'Pendiente',
                'total_precio' => $total,
            ]);

            $examenesToInsert = collect($request->examenes)
                ->map(fn($codigo) => [
                    'rayosx_order_id' => $orden->id,
                    'examen_codigo' => $codigo,
                    'created_at' => now(),
                    'updated_at' => now()
                ])
                ->toArray();

            RayosxOrderExamen::insert($examenesToInsert);

            DB::commit();

          return redirect()->route('rayosx.index')
    ->with('success', 'Orden creada correctamente, ahora puede analizarla.')
    ->with('orden_id', $orden->id);

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al guardar la orden: ' . $th->getMessage());
        }
    }

    /**
     * Mostrar una orden (con relaciones).
     */
    public function show($id)
    {
        $orden = RayosxOrder::with(['diagnostico', 'pacienteClinica', 'pacienteRayosX', 'examenes'])->findOrFail($id);
        return view('rayosX.show', compact('orden'));
    }

    /**
     * Método para recibir descripciones por examen desde el frontend.
     */
    public function guardarDescripcion(Request $request)
    {
        \Log::info('guardarDescripcion recibida:', $request->all());

        $validated = $request->validate([
            'examen' => 'required|string',
            'descripcion' => 'required|string|max:10000',
            'orden_id' => 'nullable|integer|exists:rayosx_orders,id',
            'paciente' => 'nullable|string',
        ]);

        $examen = $validated['examen'];
        $descripcion = $validated['descripcion'];

        if (!empty($validated['orden_id'])) {
            $clavePaciente = 'orden-' . $validated['orden_id'];
        } elseif (!empty($validated['paciente'])) {
            $clavePaciente = $validated['paciente'];
        } else {
            return response()->json(['success' => false, 'message' => 'No se especificó identificador de paciente/orden.'], 422);
        }

        try {
            DB::table('rayosx_descripciones')->updateOrInsert(
                ['paciente' => $clavePaciente, 'examen' => $examen],
                ['descripcion' => $descripcion, 'updated_at' => now(), 'created_at' => now()]
            );

            return response()->json(['success' => true, 'message' => 'Descripción guardada correctamente.']);
        } catch (\Throwable $e) {
            \Log::error('guardarDescripcion error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar descripción.'], 500);
        }
    }

    /**
     * Eliminar orden.
     */
    public function destroy($id)
    {
        $orden = RayosxOrder::findOrFail($id);
        $orden->delete();
        return redirect()->route('rayosx.index')->with('success', 'Orden eliminada.');
    }

    /**
     * Editar orden.
     */
    public function edit($id)
    {
        $orden = RayosxOrder::with('examenes')->findOrFail($id);
        $pacientesClinica = Paciente::orderBy('nombre')->get();
        $pacientesRayosX = PacienteRayosX::orderBy('nombre')->get();
        $diagnosticos = Diagnostico::orderBy('id','desc')->get();

        return view('rayosX.edit', compact('orden','pacientesClinica','pacientesRayosX','diagnosticos'));
    }

    /**
     * Actualizar orden.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'examenes' => 'required|array|min:1|max:10',
            'examenes.*' => 'string',
        ]);

        $orden = RayosxOrder::findOrFail($id);

        DB::beginTransaction();
        try {
            $orden->update([
                'fecha' => $request->fecha,
                'datos_clinicos' => $request->datos_clinicos,
            ]);

            if (method_exists($orden, 'examenes')) {
                $orden->examenes()->delete();
                foreach ($request->examenes as $ex) {
                    $orden->examenes()->create(['examen' => $ex]);
                }
            } else {
                RayosxOrderExamen::where('rayosx_order_id', $orden->id)->delete();
                foreach ($request->examenes as $ex) {
                    RayosxOrderExamen::create(['rayosx_order_id' => $orden->id, 'examen' => $ex]);
                }
            }

            DB::commit();
            return redirect()->route('rayosx.show', $orden->id)->with('success', 'Orden actualizada.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $th->getMessage());
        }
    }

    /**
     * Guardar paciente Rayos X.
     */
    public function storePacienteRayosX(Request $request)
    {
        $departamentosValidos = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19'];

        $rules = [
            'nombre' => ['required', 'string', 'max:50', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'apellidos' => ['required', 'string', 'max:50', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            // aquí aseguramos la tabla de migración: 'pacientes_rayosxs'
            'identidad' => [
                'required',
                'digits:13',
                'unique:pacientes_rayosxs,identidad',
                function ($attribute, $value, $fail) use ($departamentosValidos) {
                    $codigoDepartamento = substr($value, 0, 2);
                    if (!in_array($codigoDepartamento, $departamentosValidos)) {
                        return $fail('El código del departamento en la identidad no es válido.');
                    }
                    $anioNacimiento = substr($value, 4, 4);
                    $anioActual = date('Y');
                    if ($anioNacimiento < 1900 || $anioNacimiento > $anioActual) {
                        return $fail('El año de nacimiento en la identidad no es válido.');
                    }
                    $edad = $anioActual - $anioNacimiento;
                    if ($edad < 18 || $edad > 65) {
                        return $fail("La edad calculada a partir de la identidad no es válida (debe ser entre 18 y 65 años; edad actual: $edad).");
                    }
                }
            ],
            'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/', 'unique:pacientes_rayosxs,telefono'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'fecha_orden' => ['required', 'date', 'before_or_equal:today'],
        ];

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'identidad.required' => 'La identidad es obligatoria.',
            'identidad.unique' => 'La identidad ya existe en la base de datos.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'El teléfono ya está registrado.',
            'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9 y contener 8 dígitos.',
        ];

        $validated = $request->validate($rules, $messages);

        $paciente = PacienteRayosX::create($validated);

        return response()->json([
            'success' => true,
            'mensaje' => 'Paciente creado correctamente',
            'paciente' => $paciente,
        ]);
    }

    public function marcarRealizado(RayosxOrder $orden)
    {
        $orden->estado = 'Realizado';
        $orden->save();

        return redirect()->route('rayosx.index')->with('success', 'Orden marcada como realizada.');
    }

public function analisis(RayosxOrder $orden)
{
    $orden->load(['examenes', 'pacienteClinica', 'pacienteRayosX']);
    $medicosRadiologos = Medico::where('especialidad', 'Radiología')->get();

    // Mismos nombres legibles que en create()
   $examenes = [
    // Cabeza
    'craneo_anterior_posterior' => 'Cráneo Anterior Posterior',
    'craneo_lateral' => 'Cráneo Lateral',
    'waters' => 'Waters',
    'waters_lateral' => 'Waters Lateral',
    'conductos_auditivos' => 'Conductos Auditivos',
    'cavum' => 'Cavum',
    'senos_paranasales' => 'Senos Paranasales',
    'silla_turca' => 'Silla Turca',
    'huesos_nasales' => 'Huesos Nasales',
    'atm_tm' => 'ATM - TM',
    'mastoides' => 'Mastoides',
    'mandibula' => 'Mandíbula',

    // Tórax
    'torax_posteroanterior_pa' => 'Tórax PA',
    'torax_anteroposterior_ap' => 'Tórax AP',
    'torax_lateral' => 'Tórax Lateral',
    'torax_oblicuo' => 'Tórax Oblicuo',
    'torax_superior' => 'Tórax Superior',
    'torax_inferior' => 'Tórax Inferior',
    'costillas_superiores' => 'Costillas Superiores',
    'costillas_inferiores' => 'Costillas Inferiores',
    'esternon_frontal' => 'Esternón Frontal',
    'esternon_lateral' => 'Esternón Lateral',

    // Abdomen
    'abdomen_simple' => 'Abdomen Simple',
    'abdomen_agudo' => 'Abdomen Agudo',
    'abdomen_erecto' => 'Abdomen Ereto',
    'abdomen_decubito' => 'Abdomen Decúbito',

    // Extremidad superior
    'clavicula_izquierda' => 'Clavícula Izquierda',
    'clavicula_derecha' => 'Clavícula Derecha',
    'hombro_anterior' => 'Hombro Anterior',
    'hombro_lateral' => 'Hombro Lateral',
    'humero_proximal' => 'Húmero Próximal',
    'humero_distal' => 'Húmero Distal',
    'codo_anterior' => 'Codo Anterior',
    'codo_lateral' => 'Codo Lateral',
    'antebrazo' => 'Antebrazo',
    'muneca' => 'Muñeca',
    'mano' => 'Mano',

    // Extremidad inferior
    'cadera_izquierda' => 'Cadera Izquierda',
    'cadera_derecha' => 'Cadera Derecha',
    'femur_proximal' => 'Fémur Próximal',
    'femur_distal' => 'Fémur Distal',
    'rodilla_anterior' => 'Rodilla Anterior',
    'rodilla_lateral' => 'Rodilla Lateral',
    'tibia' => 'Tibia',
    'pie' => 'Pie',
    'calcaneo' => 'Calcáneo',

    // Columna y pelvis
    'columna_cervical_lateral' => 'Cervical Lateral',
    'columna_cervical_anteroposterior' => 'Cervical Anteroposterior',
    'columna_dorsal_lateral' => 'Dorsal Lateral',
    'columna_dorsal_anteroposterior' => 'Dorsal Anteroposterior',
    'columna_lumbar_lateral' => 'Lumbar Lateral',
    'columna_lumbar_anteroposterior' => 'Lumbar Anteroposterior',
    'sacro_coxis' => 'Sacro Coxis',
    'pelvis_anterior_posterior' => 'Pelvis Anterior Posterior',
    'pelvis_oblicua' => 'Pelvis Oblicua',
    'escoliosis' => 'Escoliosis',

    // Estudios especiales
    'arteriograma_simple' => 'Arteriograma Simple',
    'arteriograma_contraste' => 'Arteriograma con Contraste',
    'histerosalpingograma_simple' => 'Histerosalpingograma Simple',
    'histerosalpingograma_contraste' => 'Histerosalpingograma con Contraste',
    'colecistograma_simple' => 'Colecistograma Simple',
    'colecistograma_contraste' => 'Colecistograma con Contraste',
    'fistulograma_simple' => 'Fistulograma Simple',
    'fistulograma_contraste' => 'Fistulograma con Contraste',
    'artrograma_simple' => 'Artrograma Simple',
    'artrograma_contraste' => 'Artrograma con Contraste',
];

    return view('rayosx.analisis', compact('orden', 'medicosRadiologos', 'examenes'));
}



public function guardarAnalisis(Request $request, RayosxOrder $orden)
{
      $validated = $request->validate([
        'medico_analista_id' => 'required|exists:medicos,id',

        // 'examenes' es un array opcional
        'examenes' => 'nullable|array',

        // Para cada examen (ID) las descripciones son arrays opcionales de strings con regex
        'examenes.*.descripciones' => 'nullable|array',
        'examenes.*.descripciones.*' => [
            'nullable',
            'string',
            'max:10000',
            'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ ,.\-():]*$/'
        ],

        // Para cada examen las imagenes son arrays opcionales de imágenes
        'examenes.*.imagenes' => 'nullable|array',
        'examenes.*.imagenes.*' => 'nullable|image|max:5120', // max 5MB
    ], [
        'examenes.*.descripciones.*.regex' => 'La descripción contiene caracteres no permitidos.',
    ]);

    // Actualizar médico analista
    $orden->medico_analista_id = $validated['medico_analista_id'];
    $orden->save();

    if (!empty($validated['examenes'])) {
        foreach ($validated['examenes'] as $examenId => $datos) {
            $examen = $orden->examenes()->find($examenId);
            if (!$examen) continue;

            if (isset($datos['descripcion'])) {
                $examen->descripcion = $datos['descripcion'];
            }

            if (isset($datos['imagen']) && $datos['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                // Elimina imagen anterior si existe
                if ($examen->imagen_path) {
                    Storage::disk('public')->delete($examen->imagen_path);
                }

                // Guardar nueva imagen
                $ruta = $datos['imagen']->store('rayosx_examenes', 'public');
                $examen->imagen_path = $ruta;
            }

            $examen->save();
        }
    }
  // Cambiar estado a "Realizado" después de guardar el análisis
    $orden->estado = 'Realizado';
    $orden->save();

    return redirect()->route('rayosx.show', $orden->id)
                     ->with('success', 'Análisis guardado correctamente.');
}



}
