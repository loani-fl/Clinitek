<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    
    protected $table = 'examenes'; // <- Aquí pones el nombre correcto
    protected $fillable = ['paciente_id', 'consulta_id', 'nombre'];

    public function paciente() {
        return $this->belongsTo(Paciente::class);
    }

    public function consulta() {
        return $this->belongsTo(Consulta::class);
    }


}

