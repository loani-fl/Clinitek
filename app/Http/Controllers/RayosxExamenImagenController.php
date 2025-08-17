<?php

namespace App\Http\Controllers;

use App\Models\RayosxOrder;
use App\Models\RayosxExamenImagen;
use Illuminate\Http\Request;

class RayosxExamenImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(500, 'Error forzado para test en RayosxController@index');
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orden = RayosxOrder::with(['diagnostico','pacienteClinica','pacienteRayosX','medicoAnalista','examenes.imagenes'])
            ->findOrFail($id);
        return view('rayosX.show', compact('orden'));

        
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RayosxExamenImagen $rayosxExamenImagen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RayosxExamenImagen $rayosxExamenImagen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RayosxExamenImagen $rayosxExamenImagen)
    {
        //
    }

    public function guardarAnalisis(Request $request, RayosxOrder $orden)
{
    $validated = $request->validate([
        'medico_analista_id' => 'required|exists:medicos,id',
        'examenes' => 'nullable|array',
        'examenes.*.descripciones' => 'nullable|array',
        'examenes.*.descripciones.*' => ['nullable','string','max:10000','regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ ,.\-():]*$/'],
        'examenes.*.imagenes' => 'nullable|array',
        'examenes.*.imagenes.*' => 'nullable|image|max:5120',
    ], [
        'examenes.*.descripciones.*.regex' => 'La descripción contiene caracteres no permitidos.',
    ]);

    // Actualizar médico analista y estado
    $orden->medico_analista_id = $validated['medico_analista_id'];
    $orden->estado = 'Realizado';
    $orden->save();

    // Procesar cada examen
    $examenesInput = $request->input('examenes', []);
    foreach ($examenesInput as $examenId => $datos) {
        // Buscar examen en la orden
        $examen = $orden->examenes()->find($examenId);
        if (!$examen) continue;

        // Descripciones y archivos (pueden venir como arrays desordenados)
        $descripciones = $datos['descripciones'] ?? [];
        $imagenesFiles = $request->file("examenes.$examenId.imagenes") ?? [];

        $max = max(count($imagenesFiles), count($descripciones));

        for ($i = 0; $i < $max; $i++) {
            $file = $imagenesFiles[$i] ?? null;
            $desc = isset($descripciones[$i]) ? trim($descripciones[$i]) : null;

            if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
                $ruta = $file->store('rayosx_examenes', 'public');

                \App\Models\RayosxExamenImagen::create([
                    'rayosx_order_examen_id' => $examen->id,
                    'ruta' => $ruta,
                    'descripcion' => $desc,
                ]);
            } elseif ($desc) {
                // Si hay sólo descripción sin imagen, puedes guardarla en la tabla de descripciones genérica
                DB::table('rayosx_descripciones')->updateOrInsert(
                    ['paciente' => 'orden-'.$orden->id, 'examen' => $examen->examen_codigo ?? $examen->examen],
                    ['descripcion' => $desc, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }
    }

    return redirect()->route('rayosx.show', $orden->id)
                     ->with('success', 'Análisis guardado correctamente.');
}

}
