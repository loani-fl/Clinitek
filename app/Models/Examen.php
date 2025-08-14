<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $table = 'examens'; // Nombre de la tabla
    protected $fillable = [
        'paciente_id',
        'consulta_id',
        'nombre',
        'precio',
        'codigo'
    ];

    /**
     * Relación con el paciente
     */
    public function paciente() 
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Relación con la consulta
     */
    public function consulta() 
    {
        return $this->belongsTo(Consulta::class);
    }

    /**
     * Relación con las imágenes del examen
     */
    public function imagenes()
{
    return $this->hasMany(RayosxExamenImagen::class, 'rayosx_order_examen_id');
}

}
