<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';

    protected $fillable = [
        'nombre',
        'apellidos',
        'numero_identidad',
        'especialidad',
        'telefono',
        'correo',
        'salario',
        'identidad',
        'fecha_nacimiento',
        'fecha_ingreso',
        'genero',
        'observaciones',
        'foto',
        'sueldo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
    ];

    // Accesorios para formato legible
    public function getFechaNacimientoFormatAttribute()
    {
        return $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->format('d/m/Y') : null;
    }

    public function getFechaIngresoFormatAttribute()
    {
        return $this->fecha_ingreso ? Carbon::parse($this->fecha_ingreso)->format('d/m/Y') : null;
    }
}