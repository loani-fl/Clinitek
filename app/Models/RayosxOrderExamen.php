<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;


class RayosxOrderExamen extends Model
{
      protected $table = 'rayosx_order_examenes';

    protected $fillable = [
        'rayosx_order_id',
        'examen_codigo',
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
    

    public function paciente()
    {
        return $this->orden->paciente();
    }

     public function imagenes()

    {
        return $this->hasMany(RayosxOrderExamenImagen::class, 'rayosx_order_examen_id');
    }

    public function updateAnalisis(Request $request, $id)
{
    $orden = RayosxOrder::findOrFail($id);

    // Aquí procesas el análisis (guardar descripción, imágenes, etc.)

    return redirect()->route('rayosx.analisis', $id)
        ->with('success', 'Análisis actualizado correctamente');
}

}
