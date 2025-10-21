<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesionPsicologica;
use App\Models\Paciente;   // <<--- IMPORTANTE
use App\Models\Medico;     // <<--- si tienes modelo Medico separado

class SesionPsicologicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $query = $request->input('search', '');
        $medico = $request->input('medico', '');
        $fechaInicio = $request->input('fecha_desde');
        $fechaFin = $request->input('fecha_hasta');
        $tipoExamen = $request->input('tipo_examen');
        $perPage = 10; // Ajusta según necesites

        $totalSesiones = SesionPsicologica::count(); // ← CAMBIADO

        $sesionesQuery = SesionPsicologica::with(['paciente', 'medico']) // ← CAMBIADO
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc');

        // Filtro por paciente
        if ($query) {
            $sesionesQuery->whereHas('paciente', function($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%");
            });
        }

        // Filtro por médico
        if ($medico) {
            $sesionesQuery->whereHas('medico', function($q) use ($medico) {
                $q->where('nombre', 'like', "%$medico%")
                  ->orWhere('apellidos', 'like', "%$medico%");
            });
        }

        // Filtros de fecha
        if ($fechaInicio) $sesionesQuery->where('fecha', '>=', $fechaInicio);
        if ($fechaFin) $sesionesQuery->where('fecha', '<=', $fechaFin);

        // Filtro por tipo de examen
        if ($tipoExamen) {
            $sesionesQuery->where('tipo_examen', $tipoExamen);
        }

        $isSearch = ($query || $medico || $fechaInicio || $fechaFin || $tipoExamen);

        $totalFiltrado = $sesionesQuery->count();
        $sesiones = $sesionesQuery->paginate($perPage);

        $sesiones->appends([
            'search' => $query,
            'medico' => $medico,
            'fecha_desde' => $fechaInicio,
            'fecha_hasta' => $fechaFin,
            'tipo_examen' => $tipoExamen
        ]);

        if ($request->ajax()) {
            // Crear paginación personalizada
            $currentPage = $sesiones->currentPage();
            $lastPage = max($sesiones->lastPage(), 1);

            $customPagination = view('sesiones.custom-pagination', [
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'hasMorePages' => $sesiones->hasMorePages(),
                'onFirstPage' => $sesiones->onFirstPage(),
                'from' => $sesiones->firstItem() ?? 0,
                'to' => $sesiones->lastItem() ?? 0,
                'total' => $sesiones->total(),
            ])->render();

            return response()->json([
                'html' => view('sesiones.tabla', compact('sesiones', 'isSearch'))->render(),
                'pagination' => $customPagination,
                'total' => $totalFiltrado,
                'totalFiltrado' => $totalFiltrado,
                'all' => $totalSesiones,
            ]);
        }

        return view('sesiones.index', compact('sesiones', 'isSearch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $pacientes = Paciente::all();
        $medicos = Medico::whereIn('especialidad', ['Psiquiatría', 'Psicología'])->get();
        return view('sesiones.create', compact('pacientes', 'medicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // 1️⃣ Subir archivo temporal si existe
        if ($request->hasFile('archivo_resultado')) {
            $path = $request->file('archivo_resultado')->store('temp', 'public');
            session(['archivo_temporal' => $path]);
        }

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'motivo_consulta' => 'required|string|max:250',
            'tipo_examen' => 'required|string',
            'resultado' => 'required|string|max:300',
            'observaciones' => 'nullable|string|max:250',
            'archivo_resultado' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB

        ], [
            //paciente id
            'paciente_id.required' => 'Debe seleccionar un paciente.',

            //medico id
            'medico_id.required' => 'Debe seleccionar un médico.',

            //fecha
            'fecha.required' => 'Debe indicar la fecha de la sesión.',
            // hora inicio
            'hora_inicio.required' => 'Debe indicar la hora de inicio.',

            //hora final
            'hora_fin.required' => 'Debe indicar la hora de finalización.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            //motivo
            'motivo_consulta.required' => 'Debe ingresar el motivo de la consulta.',

            //tipo de examen
            'tipo_examen.required' => 'Debe seleccionar el tipo de examen psicométrico.',

            //reusltado
            'resultado.required' => 'Debe ingresar el resultado de la sesión.',


         'archivo_resultado.mimes' => 'El archivo debe ser JPG, PNG o PDF.',
            'archivo_resultado.max' => 'El archivo no debe superar los 5MB.',

        ]);


// Guardar archivo definitivo
        if (session('archivo_temporal')) {
            $tempPath = session('archivo_temporal');
            $finalPath = 'archivos_sesiones/' . basename($tempPath);
            \Storage::disk('public')->move($tempPath, $finalPath);
            $rutaArchivo = $finalPath;
            session()->forget('archivo_temporal');
        } else {
            $rutaArchivo = null; // <-- Definirlo aunque no haya archivo
        }

        SesionPsicologica::create([
            'paciente_id' => $request->paciente_id,
            'medico_id' =>$request->medico_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'motivo_consulta' => $request->motivo_consulta,
            'tipo_examen' => $request->tipo_examen,
            'resultado' => $request->resultado,
            'observaciones' => $request->observaciones,
            'archivo_resultado' => $rutaArchivo,
        ]);





        return redirect()->route('sesiones.index')
            ->with('success', 'Sesión psicológica registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sesion = SesionPsicologica::with(['paciente', 'medico'])->findOrFail($id);
        return view('sesiones.show', compact('sesion'));
    }



    /**
     * Show the form for editing the specified resource.
     */

}
