<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nombre',
        'apellidos',
        'identidad',             // agregado
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'correo',
        'tipo_sangre',
        'genero',                // <--- agregado aquí
        'padecimientos',
        'medicamentos',
        'historial_clinico',
        'alergias',
        'historial_quirurgico',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];
    public function diagnostico()
    {
        return $this->hasOne(Diagnostico::class);
    }

    public function consultas()
{
    return $this->hasMany(Consulta::class);
}

public function recetas()
{
    return $this->hasManyThrough(Receta::class, Consulta::class);
}

}
