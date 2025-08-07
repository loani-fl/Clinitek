<?php

namespace App\Http\Controllers;

use App\Models\PacienteRayosX;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PacienteRayosXController extends Controller
{
    /**
     * Mostrar formulario para crear paciente (solo Rayos X).
     */
    public function create()
    {
        return view('pacientes.rayosx.create');
    }

    /**
     * Guardar paciente creado desde Rayos X (tabla pacientes_rayosx).
     */
    public function store(Request $request)
    {
        // Lista de códigos de departamento válidos (ajusta si tienes otra lista)
        $departamentosValidos = [
            '01','02','03','04','05','06','07','08','09','10',
            '11','12','13','14','15','16','17','18'
        ];

        // Fechas límite para la validación de fecha_nacimiento:
        // mínimo 100 años atrás, máximo 18 años atrás
        $hoy = Carbon::today();
        $fechaMax = $hoy->copy()->subYears(18)->endOfDay();   // fecha más reciente permitida (>=18 años)
        $fechaMin = $hoy->copy()->subYears(100)->startOfDay(); // fecha más antigua permitida (<=100 años)

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            // identidad: exactamente 13 dígitos y validación personalizada
            'identidad' => [
                'required',
                'digits:13',
                'unique:pacientes_rayosxs,identidad', // <- ahora en la nueva tabla
                function ($attribute, $value, $fail) use ($departamentosValidos, $hoy) {
                    // código departamento (2 primeros dígitos)
                    $codigoDepartamento = substr($value, 0, 2);
                    if (!in_array($codigoDepartamento, $departamentosValidos)) {
                        return $fail('El código del departamento en la identidad no es válido.');
                    }

                    // año de nacimiento dentro de la identidad (posición 4, longitud 4)
                    $anioNacimiento = substr($value, 4, 4);

                    if (!ctype_digit($anioNacimiento)) {
                        return $fail('El año dentro de la identidad no tiene formato válido.');
                    }

                    $anioNacimientoInt = (int) $anioNacimiento;
                    $anioActual = (int) $hoy->format('Y');

                    if ($anioNacimientoInt < 1900 || $anioNacimientoInt > $anioActual) {
                        return $fail('El año de nacimiento en la identidad no es válido.');
                    }

                    $edadSegunIdentidad = $anioActual - $anioNacimientoInt;
                    if ($edadSegunIdentidad < 18 || $edadSegunIdentidad > 100) {
                        return $fail("La edad calculada a partir de la identidad no es válida (debe ser entre 18 y 100 años; edad: $edadSegunIdentidad).");
                    }
                },
            ],
            // fecha_orden en lugar de fecha_nacimiento (fecha en que se hace la orden)
            'fecha_orden' => ['required', 'date'],
            // solicitamos fecha_nacimiento y la validamos entre 18 y 100 años
            'fecha_nacimiento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($fechaMin, $fechaMax) {
                    try {
                        $fecha = Carbon::parse($value)->startOfDay();
                    } catch (\Exception $e) {
                        return $fail('Formato de fecha de nacimiento inválido.');
                    }

                    if ($fecha->lt($fechaMin) || $fecha->gt($fechaMax)) {
                        $minStr = $fechaMin->format('d/m/Y');
                        $maxStr = $fechaMax->format('d/m/Y');
                        return $fail("La fecha de nacimiento debe ser entre $minStr y $maxStr (edad entre 18 y 100 años).");
                    }
                },
            ],
            // telefono obligatorio, solo números, ejemplo 8 dígitos (ajusta según tu país)
            'telefono' => ['required', 'digits:8'],
        ], [
            'identidad.unique' => 'La identidad ya está registrada.',
            'identidad.digits' => 'La identidad debe tener exactamente 13 dígitos.',
            'fecha_orden.required' => 'La fecha de orden es obligatoria.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe contener exactamente 8 dígitos.',
        ]);

        // Si la validación pasó, calculamos la edad a partir de la fecha_nacimiento
        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $edadCalculada = $fechaNacimiento->age; // edad en años completos

        // (Opcional) — también derivamos año desde la identidad para guardarlo o comparar
        $anioDesdeIdentidad = (int) substr($request->identidad, 4, 4);

        // Crear paciente en la tabla pacientes_rayosx
        $paciente = PacienteRayosX::create([
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'identidad' => $request->identidad,
        'fecha_orden' => $request->fecha_orden,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'edad' => $edadCalculada,
        'telefono' => $request->telefono,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'paciente' => $paciente,
            'mensaje' => 'Paciente creado correctamente',
        ]);
    }

    return redirect()->route('rayosx.create', ['paciente_id' => $paciente->id])
                     ->with('success', 'Paciente registrado correctamente. Ahora puede crear la orden de Rayos X.');
}

    /**
     * Endpoint AJAX para validar identidad (existe o no) en pacientes_rayosx.
     * Retorna JSON: { "existe": true|false, "valid_format": true|false }
     */
    public function validarIdentidad($identidad)
    {
        // Respuesta rápida: formato 13 dígitos
        if (!preg_match('/^\d{13}$/', $identidad)) {
            return response()->json(['existe' => false, 'valid_format' => false]);
        }

        // Comprobación de existencia en la tabla pacientes_rayosx
        $existe = PacienteRayosX::where('identidad', $identidad)->exists();

        return response()->json(['existe' => $existe, 'valid_format' => true]);
    }
}
