<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultaController extends Controller
{
    // Mostrar la lista de consultas
    public function index()
    {
        $consultas = Consulta::with('paciente', 'medico')->orderBy('fecha', 'desc')->paginate(10);
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

    // Guardar la consulta en la base de datos
    public function store(Request $request)
    {
        $horaInput = trim($request->input('hora'));
        $esInmediata = $horaInput === 'inmediata';

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
            'motivo' => ['required', 'string', 'max:250'],
            'sintomas' => ['required', 'string', 'max:250'],
        ];

        if ($esInmediata) {
            $rules['total_pagar'] = ['required', 'numeric', 'min:0'];
        }

        $messages = [
            'paciente_id.required' => 'Debe seleccionar un paciente.',
            'paciente_id.exists' => 'El paciente seleccionado no es válido.',
            'hora.required' => 'Debe seleccionar una hora o elegir "Inmediata".',
            'medico_id.required' => 'Debe seleccionar un médico.',
            'medico_id.exists' => 'El médico seleccionado no es válido.',
            'motivo.required' => 'El motivo de la consulta es obligatorio.',
            'sintomas.required' => 'Los síntomas son obligatorios.',
        ];

        $validated = $request->validate($rules, $messages);

        $hora24 = null;
        if (!$esInmediata) {
            try {
                $hora24 = Carbon::createFromFormat('g:i A', $horaInput)->format('H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['hora' => 'El formato de la hora no es válido.'])->withInput();
            }

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
            'fecha' => $validated['fecha'],
            'hora' => $esInmediata ? null : $hora24,
            'especialidad' => $especialidad,
            'medico_id' => $validated['medico_id'],
            'motivo' => $validated['motivo'],
            'sintomas' => $validated['sintomas'],
            'total_pagar' => $esInmediata ? $validated['total_pagar'] : 0,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('consultas.index')->with('success', 'Consulta registrada correctamente.');
    }

    // Obtener horas ocupadas
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

    // Mostrar el formulario para editar consulta
    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);
        $pacienteSeleccionado = $consulta->paciente;
        $pacientes = Paciente::orderBy('nombre')->get();
        $medicos = Medico::orderBy('nombre')->get();

        $horas = [];
        $minutos = 8 * 60; 
        $fin = (16 * 60) + 30;

        while ($minutos <= $fin) {
            $h = floor($minutos / 60);
            $m = $minutos % 60;
            $periodo = $h >= 12 ? 'PM' : 'AM';
            $hora12 = ($h % 12 === 0 ? 12 : $h % 12);
            $minutoStr = str_pad($m, 2, '0', STR_PAD_LEFT);
            $horas[] = "{$hora12}:{$minutoStr} {$periodo}";
            $minutos += 30;
        }

        $horasOcupadas = Consulta::where('medico_id', $consulta->medico_id)
            ->where('fecha', $consulta->fecha)
            ->where('id', '!=', $consulta->id)
            ->pluck('hora')
            ->toArray();

        $inmediataOcupada = Consulta::where('medico_id', $consulta->medico_id)
            ->where('fecha', $consulta->fecha)
            ->where('hora', 'inmediata')
            ->where('id', '!=', $consulta->id)
            ->exists();

        try {
            $horaFormato12 = $consulta->hora 
                ? Carbon::createFromFormat('H:i:s', $consulta->hora)->format('g:i A') 
                : 'inmediata';
        } catch (\Exception $e) {
            $horaFormato12 = 'inmediata';
        }

        return view('consultas.edit', compact(
            'consulta', 'pacientes', 'medicos', 'horas', 'horasOcupadas', 'horaFormato12', 'inmediataOcupada'
        ));
    }

    // Actualizar la consulta en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . Carbon::now()->addMonth()->format('Y-m-d')],
            'hora' => 'required|string',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad' => 'required|string|max:100',
            'motivo' => 'required|string|max:250',
            'sintomas' => 'required|string|max:250',
            'total_pagar' => 'nullable|numeric|min:0',
        ], [
            'required' => 'Este campo es obligatorio.',
            'exists' => 'La selección no es válida.',
            'date' => 'Debe ser una fecha válida.',
            'after_or_equal' => 'La fecha no puede ser anterior a hoy.',
        ]);

        $consulta = Consulta::findOrFail($id);
        $horaInput = trim($request->hora);

        if ($horaInput === 'inmediata') {
            $consulta->hora = null;
            $consulta->total_pagar = $request->total_pagar;
        } else {
            try {
                $hora24 = Carbon::createFromFormat('H:i', $horaInput)->format('H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['hora' => 'El formato de la hora no es válido.'])->withInput();
            }

            $horaOcupada = Consulta::where('medico_id', $request->medico_id)
                ->where('fecha', $request->fecha)
                ->where('hora', $hora24)
                ->where('id', '!=', $id)
                ->exists();

            if ($horaOcupada) {
                return back()->withErrors(['hora' => 'La hora seleccionada ya está ocupada para este médico y fecha.'])->withInput();
            }

            $consulta->hora = $hora24;
            $consulta->total_pagar = null;
        }

        $consulta->paciente_id = $request->paciente_id;
        $consulta->fecha = $request->fecha;
        $consulta->medico_id = $request->medico_id;
        $consulta->especialidad = $request->especialidad;
        $consulta->motivo = $request->motivo;
        $consulta->sintomas = $request->sintomas;

        $consulta->save();

        return redirect()->route('consultas.index')->with('success', 'Consulta actualizada correctamente.');
    }

    // Cancelar consulta
    public function cancelar(Consulta $consulta)
    {
        $consulta->cancelada = true;
        $consulta->save();

        return redirect()->route('consultas.index')->with('success', 'Consulta cancelada exitosamente.');
    }
    public function show($id)
    {
        $consulta = Consulta::with('paciente', 'medico')->findOrFail($id);
        return view('consultas.show', compact('consulta'));
    }
    

}

