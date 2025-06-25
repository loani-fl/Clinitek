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
        $horaInput = $request->input('hora');
        $esInmediata = $horaInput === 'inmediata';

        $rules = [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'sexo' => ['required', 'in:Femenino,Masculino'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'string', 'max:20'],
        ];

        if (!$esInmediata) {
            $rules += [
                'medico_id' => ['required', 'exists:medicos,id'],
                'motivo' => ['required', 'string', 'max:300'],
                'sintomas' => ['required', 'string', 'max:300'],
            ];
        }

        $messages = [
            'paciente_id.required' => 'Debe seleccionar un paciente.',
            'paciente_id.exists' => 'El paciente seleccionado no es válido.',
            'sexo.required' => 'El genero es obligatorio.',
            'sexo.in' => 'Seleccione un sexo válido.',
            'fecha.required' => 'La fecha de la consulta es obligatoria.',
            'fecha.date' => 'La fecha debe ser válida.',
            'hora.required' => 'Debe seleccionar una hora o elegir "Inmediata".',
            'hora.max' => 'La hora no puede exceder 20 caracteres.',
            'medico_id.required' => 'Debe seleccionar un médico.',
            'medico_id.exists' => 'El médico seleccionado no es válido.',
            'motivo.required' => 'El motivo de la consulta es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 300 caracteres.',
            'sintomas.required' => 'Los síntomas son obligatorios.',
            'sintomas.max' => 'Los síntomas no pueden exceder 300 caracteres.',
        ];

        $validated = $request->validate($rules, $messages);

        $hora24 = null;

        if (!$esInmediata) {
            try {
                $hora24 = Carbon::createFromFormat('h:i A', $horaInput)->format('H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['hora' => 'El formato de la hora no es válido.'])->withInput();
            }

            $existe = Consulta::where('medico_id', $request->medico_id)
                ->where('fecha', $validated['fecha'])
                ->where('hora', $hora24)
                ->exists();

            if ($existe) {
                return back()
                    ->withErrors(['hora' => 'El médico ya tiene una consulta registrada en esa fecha y hora.'])
                    ->withInput();
            }
        }

        Consulta::create([
            'paciente_id' => $validated['paciente_id'],
            'sexo' => $validated['sexo'],
            'fecha' => $validated['fecha'],
            'hora' => $esInmediata ? null : $hora24,
            'especialidad' => $esInmediata ? null : $request->especialidad,
            'medico_id' => $esInmediata ? null : $request->medico_id,
            'motivo' => $esInmediata ? null : $request->motivo,
            'sintomas' => $esInmediata ? null : $request->sintomas,
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
