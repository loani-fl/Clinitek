<?php

namespace App\Http\Controllers;
use App\Models\Puesto;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    
    public function create()
    {
        $puestos = Puesto::all();
        return view('empleados.create', compact('puestos'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'nombres'            => 'required|string|max:255',
'apellidos'         => 'required|string|max:255',
'identidad'         => 'required|unique:empleados,identidad',
'direccion'         => 'required|string',
'telefono'          => 'required|string|max:15',
'correo'            => 'required|email|unique:empleados,correo',
'fecha_ingreso'     => 'required|date',
'fecha_nacimiento'  => 'required|date',
'genero'            => 'required',
'estado_civil'      => 'required',
'puesto_id'         => 'required|exists:puestos,id',
'salario'           => 'required|numeric|min:0',
'observaciones'     => 'nullable|string',

        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.create')->with('success', 'Empleado registrado exitosamente.');
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
