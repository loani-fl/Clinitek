<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    // Mostrar la lista de consultas
    //Nuevo index


    
public function index()
{
    $consultas = Consulta::with([
        'paciente',
        'medico',
        'diagnostico',
        'recetas',   // plural
        'examens'    // plural
    ])
    ->orderBy('fecha', 'desc')
    ->paginate(4);

    $medicos = Medico::orderBy('nombre')->get();

    return view('consultas.index', compact('consultas', 'medicos'));
}


    // Mostrar el formulario para crear consulta
    public function create()
    {
        $pacientes = Paciente::select(
            'id',
            'nombre',
            'apellidos',
            'identidad',
            'fecha_nacimiento',
            'telefono',
            'correo',
            'direccion',
            'genero'
        )->orderBy('nombre')->get();

        $medicos = Medico::select('id', 'nombre', 'apellidos', 'especialidad')
            ->orderBy('nombre')
            ->get();

        return view('consultas.create', compact('pacientes', 'medicos'));
    }

public function store(Request $request)
{
    $horaInput = trim($request->input('hora'));
    $esInmediata = $horaInput === 'inmediata';

    // 🔹 Reglas de validación
    $rules = [
        'paciente_id' => ['required', 'exists:pacientes,id'],
        'fecha' => ['required', 'date'],
        'hora' => [
            'required',
            function ($attribute, $value, $fail) {
                $value = trim($value);
                if ($value !== 'inmediata') {
                    $horaValida = \DateTime::createFromFormat('g:i A', $value);
                    if (!($horaValida && $horaValida->format('g:i A') === $value)) {
                        $fail('El formato de la hora no es válido. Usa por ejemplo: 9:00 AM.');
                    }
                }
            },
        ],
        'medico_id' => ['required', 'exists:medicos,id'],
        'motivo' => ['required', 'string', 'max:250', 'regex:/^[\pL\pN\s.,;:()¡!¿?"“”\'\-]+$/u'],
        'sintomas' => ['required', 'string', 'max:250', 'regex:/^[\pL\pN\s.,;:()¡!¿?"“”\'\-]+$/u'],
    ];

    // Si es inmediata, el total a pagar es obligatorio
    if ($esInmediata) {
        $rules['total_pagar'] = ['required', 'numeric', 'min:0'];
    }

    // 🔹 Mensajes personalizados
    $messages = [
        'paciente_id.required' => 'Debe seleccionar un paciente.',
        'paciente_id.exists' => 'El paciente seleccionado no es válido.',
        'hora.required' => 'Debe seleccionar una hora o elegir "Inmediata".',
        'medico_id.required' => 'Debe seleccionar un médico.',
        'medico_id.exists' => 'El médico seleccionado no es válido.',
        'motivo.required' => 'El motivo de la consulta es obligatorio.',
        'sintomas.required' => 'Los síntomas son obligatorios.',
        'motivo.regex' => 'El motivo solo debe contener caracteres permitidos.',
        'sintomas.regex' => 'Los síntomas contienen caracteres no permitidos.',
    ];

    $validated = $request->validate($rules, $messages);

    // 🔹 Convertir hora a formato 24h
    $hora24 = $esInmediata ? now()->format('H:i:s') : Carbon::createFromFormat('g:i A', $horaInput)->format('H:i:s');

    // 🔹 Validar conflicto de horario solo si NO es inmediata
    if (!$esInmediata) {
        $existe = Consulta::where('medico_id', $validated['medico_id'])
            ->where('fecha', $validated['fecha'])
            ->where('hora', $hora24)
            ->exists();

        if ($existe) {
            return back()->withErrors(['hora' => 'El médico ya tiene una consulta registrada en esa fecha y hora.'])->withInput();
        }
    }

    $medico = Medico::find($validated['medico_id']);
    $especialidad = $medico ? $medico->especialidad : null;

    // 🔹 Crear la consulta
    $consulta = Consulta::create([
        'paciente_id' => $validated['paciente_id'],
        'fecha' => $validated['fecha'],
        'hora' => $hora24,
        'especialidad' => $especialidad,
        'medico_id' => $validated['medico_id'],
        'motivo' => $validated['motivo'],
        'sintomas' => $validated['sintomas'],
        'total_pagar' => $esInmediata ? $validated['total_pagar'] : 0,
        'estado' => 'pendiente',
    ]);

    // 🔹 Crear el pago asociado
    $pago = \App\Models\Pago::create([
        'paciente_id' => $validated['paciente_id'],
        'metodo_pago' => 'pendiente',
        'fecha' => now()->format('Y-m-d'),
        'origen' => 'consulta',
        'referencia_id' => $consulta->id,
        'servicio' => 'Consulta médica',
        'monto' => $esInmediata ? $validated['total_pagar'] : 0,
    ]);

    // 🔹 Crear la factura usando la consulta guardada
    $paciente = Paciente::find($validated['paciente_id']);
    $factura = \App\Models\Factura::crearDesdeConsulta($consulta, $paciente, $medico);

    // 🔹 Redirigir al show de la factura
    return redirect()->route('factura.show', $factura->id)
                     ->with('success', 'Consulta registrada y factura generada correctamente.');
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'fecha' => ['required', 'date', 'after_or_equal:today'],
        'medico_id' => ['required', 'exists:medicos,id'],
        'hora' => ['required', 'date_format:g:i A'],  // Formato 12h con AM/PM
        'motivo' => ['required', 'string', 'max:250', 'regex:/^[a-zA-Z0-9\s.,áéíóúÁÉÍÓÚñÑ\-]+$/u'],
        'sintomas' => ['required', 'string', 'max:250', 'regex:/^[a-zA-Z0-9\s.,áéíóúÁÉÍÓÚñÑ\-]+$/u'],
    ], [
        'hora.required' => 'Debe seleccionar una hora.',
        'hora.date_format' => 'El formato de la hora debe ser h:mm AM/PM (ejemplo: 3:30 PM).',
        'motivo.regex' => 'El motivo solo puede contener letras, números, espacios, comas, puntos y guiones.',
        'sintomas.regex' => 'Los síntomas solo pueden contener letras, números, espacios, comas, puntos y guiones.',
    ]);

    // Convertir la hora 12h a formato 24h para guardar en BD
    $hora24 = Carbon::createFromFormat('g:i A', $validated['hora'])->format('H:i:s');

    $consulta = Consulta::findOrFail($id);
    $consulta->fecha = $validated['fecha'];
    $consulta->medico_id = $validated['medico_id'];
    $consulta->hora = $hora24;
    $consulta->motivo = $validated['motivo'];
    $consulta->sintomas = $validated['sintomas'];
    $consulta->save();

    return redirect()->route('consultas.index')->with('success', 'Consulta actualizada correctamente.');
}

    public function show($id)
    {
        $consulta = Consulta::with(['paciente', 'medico', 'diagnostico', 'recetas'])->findOrFail($id);
        $paciente = $consulta->paciente;

        // Verificar si ya hay receta creada
        $tieneReceta = $consulta->recetas->isNotEmpty();
        $tieneExamen = $consulta->examens->isNotEmpty(); // true si hay exámenes



        return view('consultas.show', compact('consulta', 'paciente', 'tieneReceta','tieneExamen'));
    }


    public function horasOcupadas(Request $request)
{
    $medicoId = $request->query('medico_id');
    $fecha = $request->query('fecha');

    if (!$medicoId || !$fecha) {
        return response()->json([]);
    }

    $horasOcupadas = Consulta::where('medico_id', $medicoId)
        ->where('fecha', $fecha)
        ->pluck('hora')
        ->toArray();

    return response()->json($horasOcupadas);
}

public function cambiarEstado(Request $request, $id)
{
    $consulta = Consulta::findOrFail($id);

    $consulta->estado = $consulta->estado === 'pendiente' ? 'cancelada' : 'pendiente';

    $consulta->save();

    return redirect()->back()->with('success', 'Estado de la consulta actualizado.');
}

}
