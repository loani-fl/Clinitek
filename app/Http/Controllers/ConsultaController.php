<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultaController extends Controller
{
    public function index()
    {
        $consultas = Consulta::with('paciente', 'medico')->orderBy('fecha', 'desc')->paginate(10);
        return view('consultas.index', compact('consultas'));
    }

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
            'direccion'
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

    $rules = [
        'paciente_id' => ['required', 'exists:pacientes,id'],
        'sexo' => ['required', 'in:Femenino,Masculino'],
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
        'motivo' => ['required', 'string', 'max:250'],
        'sintomas' => ['required', 'string', 'max:250'],
    ];

    // Agregar total_pagar solo si es inmediata
    if ($esInmediata) {
        $rules['total_pagar'] = ['required', 'numeric', 'min:0'];
    }

    $messages = [
        'paciente_id.required' => 'Debe seleccionar un paciente.',
        'paciente_id.exists' => 'El paciente seleccionado no es válido.',
        'sexo.required' => 'El género es obligatorio.',
        'sexo.in' => 'Seleccione un sexo válido.',
        'fecha.required' => 'La fecha de la consulta es obligatoria.',
        'fecha.date' => 'La fecha debe ser válida.',
        'hora.required' => 'Debe seleccionar una hora o elegir "Inmediata".',
        'medico_id.required' => 'Debe seleccionar un médico.',
        'medico_id.exists' => 'El médico seleccionado no es válido.',
        'motivo.required' => 'El motivo de la consulta es obligatorio.',
        'motivo.max' => 'El motivo no puede exceder 250 caracteres.',
        'sintomas.required' => 'Los síntomas son obligatorios.',
        'sintomas.max' => 'Los síntomas no pueden exceder 250 caracteres.',
        'total_pagar.required' => 'Debe indicar el total a pagar.',
        'total_pagar.numeric' => 'El total debe ser un número.',
        'total_pagar.min' => 'El total debe ser mayor o igual a 0.',
    ];

    $validated = $request->validate($rules, $messages);

    // Convertir la hora si no es inmediata
    $hora24 = null;
    if (!$esInmediata) {
        try {
            $hora24 = Carbon::createFromFormat('g:i A', $horaInput)->format('H:i:s');
        } catch (\Exception $e) {
            return back()->withErrors(['hora' => 'El formato de la hora no es válido.'])->withInput();
        }

        // Verificar si la hora ya está ocupada
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

    Consulta::create([
        'paciente_id' => $validated['paciente_id'],
        'sexo' => $validated['sexo'],
        'fecha' => $validated['fecha'],
        'hora' => $esInmediata ? null : $hora24,
        'especialidad' => $especialidad,
        'medico_id' => $validated['medico_id'],
        'motivo' => $validated['motivo'],
        'sintomas' => $validated['sintomas'],
        'total_pagar' => $esInmediata ? $validated['total_pagar'] : 0,
    ]);

    return redirect()->route('consultas.index')->with('success', 'Consulta registrada correctamente.');
}


    public function horasOcupadas(Request $request)
    {
        $medicoId = $request->query('medico_id');
        $fecha = $request->query('fecha');

        $ocupadas = Consulta::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->pluck('hora')
            ->toArray();

        return response()->json($ocupadas);
    }
}
