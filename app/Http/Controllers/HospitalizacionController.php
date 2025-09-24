<?php

namespace App\Http\Controllers;

use App\Models\Emergencia;
use App\Models\Paciente;
use App\Models\Hospitalizacion;
use Illuminate\Http\Request;

class HospitalizacionController extends Controller
{
    public function create($emergencia_id)
    {
        // Obtiene la emergencia
        $emergencia = Emergencia::findOrFail($emergencia_id);

        // Redirige al formulario con los datos de la emergencia
        return view('Hospitalizacion.transferirHospitalizacion', compact('emergencia'));

    }

    public function store(Request $request)
{
    $request->validate([
        'acompanante' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s]+$/u'
        ],
        'fecha_ingreso' => 'required|date',
        'motivo' => [
            'required',
            'string',
            'max:150',
            'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,;:()\-]+$/u'
        ],
    ], [
        'acompanante.required' => 'El campo acompañante es obligatorio.',
        'acompanante.max' => 'El campo Acompañante no puede tener más de 100 caracteres.',
        'acompanante.regex' => 'El campo acompañante solo puede contener letras, números y espacios.',
        'fecha_ingreso.required' => 'Debes seleccionar la fecha y hora de ingreso.',
        'fecha_ingreso.date' => 'La fecha de ingreso debe ser válida.',
        'motivo.required' => 'El campo motivo de hospitalización es obligatorio.',
        'motivo.max' => 'El Motivo de hospitalización no puede exceder los 150 caracteres.',
        'motivo.regex' => 'El Motivo de hospitalización solo puede contener letras, números, espacios y algunos signos (.,;:()-).',
    ]);

    // Crear o actualizar paciente
    $paciente = Paciente::updateOrCreate(
        ['identidad' => $request->identidad],
        [
            'nombre' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento ?? now(),
            'direccion' => $request->direccion ?? 'Desconocida',
            'genero' => $request->sexo ?? 'No especificado',
            'padecimientos' => $request->padecimientos ?? 'Ninguno'
        ]
    );

    // Crear hospitalización
    Hospitalizacion::create([
        'paciente_id' => $paciente->id,
        'fecha_ingreso' => $request->fecha_ingreso,
        'motivo' => $request->motivo,
        'acompanante' => $request->acompanante,
    ]);

    return redirect()->route('emergencias.show', $request->emergencia_id)
                     ->with('success', 'Paciente transferido a hospitalización correctamente.');
}

public function index() {
    $hospitalizaciones = Hospitalizacion::all();
    return view('hospitalizacion.index', compact('hospitalizaciones'));
}


    
}
