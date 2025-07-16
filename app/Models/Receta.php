<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'consulta_id',
        'detalles',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

// Relación con el diagnóstico
    public function diagnostico()
    {
        return $this->hasOne(Diagnostico::class);
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_receta', 'receta_id', 'medicamento_id')
                    ->withPivot('indicaciones', 'dosis', 'detalles')
                    ->withTimestamps();
    }
}