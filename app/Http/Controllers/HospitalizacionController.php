<?php

namespace App\Http\Controllers;

use App\Models\Emergencia;
use App\Models\Hospitalizacion;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HospitalizacionController extends Controller
{
    // Mostrar formulario de creación
    public function create($emergencia_id = null)
    {
        $emergencia = $emergencia_id ? Emergencia::findOrFail($emergencia_id) : null;
        $paciente = $emergencia->paciente ?? null;
        $medicos = Medico::all();

        return view('Hospitalizacion.create', compact('emergencia', 'paciente', 'medicos'));
    }

    // Guardar hospitalización y redirigir a impresión
    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'acompanante' => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s]+$/u'],
            'fecha_ingreso' => 'required|date',
            'motivo' => ['required','string','max:150','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,;:()\-]+$/u'],
            'hospital' => 'required|string|max:150',
            'medico_id' => 'required|exists:medicos,id',
            'clinica' => ['required','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,-]+$/u'],
            'emergencia_id' => 'required|exists:emergencias,id',
        ], [
            'acompanante.max' => 'El campo Acompañante no puede tener más de 100 caracteres.',
            'acompanante.regex' => 'El campo Acompañante solo puede contener letras, números y espacios.',
            'fecha_ingreso.required' => 'Debes seleccionar la fecha y hora de ingreso.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser válida.',
            'motivo.required' => 'El campo Motivo de hospitalización es obligatorio.',
            'motivo.max' => 'El Motivo de hospitalización no puede exceder los 150 caracteres.',
            'motivo.regex' => 'El Motivo de hospitalización solo puede contener letras, números, espacios y algunos signos (.,;:()-).',
            'hospital.required' => 'Debes seleccionar un hospital de destino.',
            'hospital.max' => 'El nombre del hospital no puede exceder los 150 caracteres.',
            'medico_id.required' => 'Debes seleccionar el médico que remite.',
            'medico_id.exists' => 'El médico seleccionado no existe en la base de datos.',
            'clinica.required' => 'Debes ingresar el nombre de la clínica.',
            'clinica.max' => 'El nombre de la clínica no puede exceder los 100 caracteres.',
            'clinica.regex' => 'El nombre de la clínica solo puede contener letras, números, espacios y algunos signos (.,-).',
            'emergencia_id.required' => 'Debe seleccionarse la emergencia asociada.',
            'emergencia_id.exists' => 'La emergencia seleccionada no existe.',
        ]);

        // Crear o usar paciente existente
        $paciente = Paciente::firstOrCreate(
            ['identidad' => $request->identidad ?? '0000000000000'],
            [
                'nombre' => $request->nombres ?? 'Desconocido',
                'apellidos' => $request->apellidos ?? 'Desconocido',
                'telefono' => $request->telefono ?? '00000000',
                'fecha_nacimiento' => $request->fecha_nacimiento ?? now(),
                'direccion' => $request->direccion ?? 'Desconocida',
                'genero' => $request->sexo ?? 'Desconocido',
                'padecimientos' => $request->padecimientos ?? 'Ninguno',
                'medicamentos' => $request->medicamentos ?? 'Ninguno',
                'historial_clinico' => $request->historial_clinico ?? 'Ninguno',
                'alergias' => $request->alergias ?? 'Ninguno',
                'historial_quirurgico' => $request->historial_quirurgico ?? 'Ninguno',
            ]
        );

        // Registrar hospitalización
        $hospitalizacion = Hospitalizacion::create([
            'paciente_id' => $paciente->id,
            'emergencia_id' => $request->emergencia_id,
            'acompanante' => $request->acompanante,
            'fecha_ingreso' => $request->fecha_ingreso,
            'motivo' => $request->motivo,
            'hospital' => $request->hospital,
            'medico_id' => $request->medico_id,
            'clinica' => $request->clinica,
        ]);

        // Cargar relaciones para impresión
        $hospitalizacion->load('paciente', 'medico', 'emergencia');

        // Redirigir a la vista de impresión
        return redirect()->route('hospitalizaciones.imprimir', $hospitalizacion->id);
    }

    // Mostrar hospitalización para impresión
   public function imprimir(Hospitalizacion $hospitalizacion)
{
    // Cargar relaciones básicas
    $hospitalizacion->load('paciente', 'medico', 'emergencia');

    // Si la hospitalización no tiene emergencia asociada, tomar la última del paciente
    if (!$hospitalizacion->emergencia && $hospitalizacion->paciente) {
        $hospitalizacion->emergencia = $hospitalizacion->paciente->emergencias()->latest()->first();
    }

    return view('Hospitalizacion.imprimir', compact('hospitalizacion'));
}

    // Mostrar hospitalización específica
    public function show($id)
    {
        $hospitalizacion = Hospitalizacion::findOrFail($id);
        $hospitalizacion->load('paciente', 'medico', 'emergencia');
        return view('Hospitalizacion.show', compact('hospitalizacion'));
    }
}
