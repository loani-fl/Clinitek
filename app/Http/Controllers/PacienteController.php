<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function create()
    {
        return view('pacientes.create');
    }

    public function createConConsulta($paciente_id, $consulta_id)
    {
        $consulta = Consulta::findOrFail($consulta_id);

        if ($consulta->estado !== 'realizada') {
            return redirect()->back()->with('error', 'Antes debe realizarse un diagnóstico para poder crear la orden de examen.');
        }

        return view('pacientes.create', compact('paciente_id', 'consulta_id'));
    }

    private function validarAnioIdentidad($identidad)
    {
        $anio = (int)substr($identidad, 4, 4);
        $anioActual = (int)date('Y');
        return ($anio >= 1930 && $anio <= $anioActual);
    }

    public function store(Request $request)
    {
        $anioActual = date('Y');

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'identidad' => [
                'required',
                'digits:13',
                'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(\d{4})\d{5}$/',
                'unique:pacientes,identidad'
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(21)->format('Y-m-d'),
                'after_or_equal:' . Carbon::now()->subYears(60)->format('Y-m-d'),
            ],
            'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/', 'unique:pacientes,telefono'],
            'direccion' => ['required', 'string', 'max:300'],
            'correo' => [
                'required',
                'email',
                'max:50',
                'unique:pacientes,correo',
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
            ],
            'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'genero' => ['required', 'in:Femenino,Masculino,Otro'],
            'padecimientos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'medicamentos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_clinico' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'alergias' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_quirurgico' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:200'],
        ]);

        if (!$this->validarAnioIdentidad($request->identidad)) {
            return redirect()->back()
                ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
                ->withInput();
        }

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado exitosamente.');
    }

    public function index(Request $request)
    {
        $query = $request->input('search', '');
        $pacientesQuery = Paciente::query();

        if ($query) {
            $pacientesQuery->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%")
                  ->orWhere('identidad', 'like', "%$query%");
            });
            $pacientes = $pacientesQuery->get();
        } else {
            $pacientes = $pacientesQuery->paginate(2);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pacientes.partials.tabla', compact('pacientes'))->render(),
                'pagination' => ($pacientes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    ? view('pagination::bootstrap-5', ['paginator' => $pacientes])->render()
                    : '',
                'total' => $pacientes->count(),
                'all' => Paciente::count(),
            ]);
        }

        return view('pacientes.index', compact('pacientes'));
    }

    public function show($id)
    {
        $paciente = Paciente::with(['consultas.receta', 'diagnostico'])->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $anioActual = date('Y');

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'genero' => ['required', 'in:Femenino,Masculino,Otro'],
            'identidad' => [
                'required',
                'digits:13',
                'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(\d{4})\d{5}$/',
                Rule::unique('pacientes', 'identidad')->ignore($paciente->id)
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(21)->format('Y-m-d'),
                'after_or_equal:' . Carbon::now()->subYears(60)->format('Y-m-d'),
            ],
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[2389][0-9]{7}$/',
                Rule::unique('pacientes', 'telefono')->ignore($paciente->id),
            ],
            'direccion' => ['required', 'string', 'max:300'],
            'correo' => [
                'required',
                'email',
                'max:50',
                Rule::unique('pacientes', 'correo')->ignore($paciente->id),
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
            ],
            'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'padecimientos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'medicamentos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_clinico' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'alergias' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_quirurgico' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:200'],
        ]);

        if (!$this->validarAnioIdentidad($request->identidad)) {
            return redirect()->back()
                ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
                ->withInput();
        }

        $paciente->update($request->only([
            'nombre',
            'apellidos',
            'identidad',
            'fecha_nacimiento',
            'telefono',
            'direccion',
            'correo',
            'tipo_sangre',
            'genero',
            'padecimientos',
            'medicamentos',
            'historial_clinico',
            'alergias',
            'historial_quirurgico',
        ]));

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function showRecetas($pacienteId)
    {
        $paciente = Paciente::with('recetas')->findOrFail($pacienteId);
        return view('recetas.show', compact('paciente'));
    }
}
