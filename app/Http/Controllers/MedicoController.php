<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;
use Illuminate\Validation\Rule;
use Carbon\Carbon;



class MedicoController extends Controller
{
    /**
     * Mostrar formulario para crear nuevo médico
     */
    public function create()
    {
        return view('medicos.create');
    }

    /**
     * Guardar nuevo médico en base de datos
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|regex:/^[\pL]+$/u|max:50',
            'apellidos' => 'required|regex:/^[\pL]+$/u|max:50',
            'especialidad' => 'required|string|max:80',
            'telefono' => 'required|numeric|unique:medicos,telefono|digits:8',
            'correo' => 'required|email|unique:medicos,correo|max:100',
            'fecha_nacimiento' => 'required|date|after_or_equal:1950-01-01|before_or_equal:today',
            'fecha_ingreso' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
            'genero' => 'required',
            'observaciones' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [

            'telefono.unique' => 'Este número de teléfono ya está registrado por otro médico.',
            'correo.unique' => 'Este correo electrónico ya está registrado por otro médico.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras, espacios y guiones.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'genero.required' => 'El género es obligatorio.',
        ]);
        $datosMedico = $request->only([
            'nombre',
            'apellidos',
            'especialidad',
            'telefono',
            'correo',
            'fecha_nacimiento',
            'fecha_ingreso',
            'genero',
            'observaciones',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('fotos_medicos', 'public');
            $datosMedico['foto'] = $foto;
        }

        // Crear y guardar el médico
        Medico::create($datosMedico);



        // ✅ Redirigir a la lista de médicos (NO al formulario)
        return redirect()->route('medicos.index')->with('success', 'Médico registrado exitosamente');
    }

    /**
     * Mostrar lista de médicos
     */

    public function index(Request $request)
    {
        $query = Medico::query();

        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('especialidad', 'like', "%{$buscar}%");
        }


        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        $medicos = $query->paginate(10);
        $medicos->appends($request->only('buscar'));


        return view('medicos.index', compact('medicos'));

    }


    public function show($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.show', compact('medico'));
    }


    /**
     * Mostrar formulario para editar médico existente
     */
    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.edit', compact('medico'));
    }

    /**
     * Actualizar médico existente
     */
    public function update(Request $request, $id)
    {

        $hoy = Carbon::today();
        $fechaMin = $hoy->copy()->subMonth()->format('Y-m-d');
        $fechaMax = $hoy->copy()->addMonth()->format('Y-m-d');

        // Validar datos
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
            'apellidos' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
            'especialidad' => 'required|string|max:80',
            'telefono' => [
                'required',
                'regex:/^[983]\d{7}$/',
                'unique:medicos,telefono,' . $id,
            ],

            'correo' => [
                'required',
                'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,}$/',
                'max:100',
                Rule::unique('medicos', 'correo')->ignore($id),
            ],
            'salario' => ['nullable', 'regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'identidad' => 'required|digits:13|unique:medicos,identidad,' . $id . ',id',
            'fecha_nacimiento' => 'required|date|after_or_equal:1950-01-01|before_or_equal:2005-12-31',
            'fecha_ingreso' => [
                'required',
                'date',
                'after_or_equal:' . $fechaMin,
                'before_or_equal:' . $fechaMax,
            ],
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'observaciones' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'estado' => 'nullable|boolean',


        ], [
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento debe ser igual o anterior al 31 de diciembre de 2005.',
            'fecha_ingreso.after_or_equal' => 'La fecha de ingreso no puede ser anterior a un mes antes de hoy.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser posterior a un mes después de hoy.',
            'telefono.regex' => 'El teléfono debe iniciar con 9, 8 o 3 y contener 8 dígitos.',
            'telefono.unique' => 'Este número de teléfono ya está registrado por otro médico.',
            'correo.unique' => 'Este correo electrónico ya está registrado por otro médico.',
            'correo.regex' => 'El correo debe incluir un dominio válido, por ejemplo .com, .net, .es',
            'correo.regex' => 'El correo debe contener un @ y un dominio válido como .com, .net, .org, etc.',
            'identidad.unique' => 'Este número de identidad ya está registrado por otro médico.',
            'identidad.digits' => 'El campo identidad debe tener exactamente 13 dígitos.',




        ]);



        // Actualizar el médico
        $medico = Medico::findOrFail($id);

        // Verificar si se cargó una nueva foto
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($medico->foto && \Storage::disk('public')->exists($medico->foto)) {
                \Storage::disk('public')->delete($medico->foto);
            }

            $rutaFoto = $request->file('foto')->store('fotos_medicos', 'public');
            $medico->foto = $rutaFoto;
        }


        $medico->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'salario' => $request->salario,
            'identidad' => $request->identidad,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'observaciones' => $request->observaciones,
            'estado' => $request->estado,
            'foto' => $medico->foto,


        ]);

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado exitosamente');
    }

    public function destroy($id)
    {
        $medico = Medico::findOrFail($id); // Busca el médico o lanza 404 si no existe
        $medico->delete(); // Elimina el médico

        return redirect()->route('medicos.index')
            ->with('success', 'Médico eliminado correctamente.');
    }
    public function toggleEstado(Medico $medico)
    {
        $medico->estado = !$medico->estado;
        $medico->save();

        return response()->json(['estado' => $medico->estado]);
    }


}
