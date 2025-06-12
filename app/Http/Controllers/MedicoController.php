<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Medico::query();

        if ($request->filled('buscar')) {
            $busqueda = $request->input('buscar');
            $query->where('nombre', 'like', "%$busqueda%")
                  ->orWhere('especialidad', 'like', "%$busqueda%");
        }

        $medicos = $query->paginate(10);

        return view('medicos.index', compact('medicos'));
    }

    public function create()
    {
        $especialidades = [
            'Cardiología',
            'Neurología',
            'Pediatría',
            'Dermatología',
            'Psiquiatría',
            'Radiología',
        ];

        return view('medicos.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'numero_identidad' => preg_replace('/\D/', '', $request->input('numero_identidad')),
        ]);

        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'apellidos' => ['required', 'string', 'max:50', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'numero_identidad' => ['required', 'regex:/^\d{13}$/', 'unique:medicos,numero_identidad'],
            'telefono' => ['required', 'digits:8', 'regex:/^[8932]\d{7}$/', 'unique:medicos,telefono'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'fecha_ingreso' => ['required', 'date', 'after_or_equal:' . now()->subMonth()->format('Y-m-d'), 'before_or_equal:' . now()->addMonth()->format('Y-m-d')],
            'especialidad' => ['required', 'string', 'in:Cardiología,Neurología,Pediatría,Dermatología,Psiquiatría,Radiología'],
            'sueldo' => ['required', 'numeric', 'min:1000'],
            'correo' => ['required', 'string', 'max:50', 'unique:medicos,correo', 'email'],
            'genero' => ['required', 'string', 'in:Masculino,Femenino,Otro'],
            'direccion' => ['nullable', 'string', 'max:150'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ],
        [
            // Mensajes personalizados para todos los campos comunes
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.string' => 'Los apellidos deben ser una cadena de texto.',
            'apellidos.max' => 'Los apellidos no pueden tener más de 50 caracteres.',
            'apellidos.regex' => 'Los apellidos solo deben contener letras y espacios.',

            'numero_identidad.required' => 'El número de identidad es obligatorio.',
            'numero_identidad.regex' => 'El número de identidad debe tener exactamente 13 dígitos.',
            'numero_identidad.unique' => 'Este número de identidad ya está registrado.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El teléfono debe comenzar con 8, 9, 3 o 2.',
            'telefono.unique' => 'Este teléfono ya está registrado.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no es una fecha válida.',
            'fecha_nacimiento.before_or_equal' => 'El médico debe ser mayor de 18 años.',

            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso no es una fecha válida.',
            'fecha_ingreso.after_or_equal' => 'La fecha de ingreso debe ser reciente.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser en el futuro.',

            'especialidad.required' => 'La especialidad es obligatoria.',
            'especialidad.in' => 'La especialidad seleccionada no es válida.',

            'sueldo.required' => 'El sueldo es obligatorio.',
            'sueldo.numeric' => 'El sueldo debe ser un número.',
            'sueldo.min' => 'El sueldo mínimo es de 1000.',

            'correo.required' => 'El correo es obligatorio.',
            'correo.string' => 'El correo debe ser una cadena de texto.',
            'correo.max' => 'El correo no puede tener más de 50 caracteres.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'correo.email' => 'El correo debe contener un "@" y un punto (.) en el dominio.',

            'genero.required' => 'Por favor, elija una opción para el género.',
            'genero.in' => 'La opción de género seleccionada no es válida.',

            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede tener más de 150 caracteres.',

            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La imagen no puede superar los 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/fotos_medicos', $nombreArchivo);
            $validatedData['foto'] = $nombreArchivo;
        }

        Medico::create($validatedData);

        return redirect()->route('medicos.index')->with('success', 'Médico registrado correctamente.');
    }
}
