<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentado', 'paciente_id', 'foto', 'direccion',
        'fecha', 'hora', 'motivo', 'pa', 'fc', 'temp'
    ];

    protected $casts = [
        'documentado' => 'boolean',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}


