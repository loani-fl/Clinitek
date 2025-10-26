<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'categoria',
        'descripcion',
        'cantidad',
        'unidad',
        'fecha_ingreso',
        'fecha_vencimiento',
        'precio_unitario',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public static function generarCodigoPorCategoria($categoria, $idExcluir = null)
{
    // Normalizamos el prefijo (3 letras mayúsculas)
    $prefijo = strtoupper(substr($categoria, 0, 3));

    // Buscamos TODOS los códigos con ese prefijo (excluyendo el actual si existe)
    $query = self::where('codigo', 'like', $prefijo . '-%');
    
    if ($idExcluir) {
        $query->where('id', '!=', $idExcluir);
    }
    
    // Obtener todos los números existentes
    $codigosExistentes = $query->pluck('codigo')->toArray();
    
    // Extraer solo los números de cada código
    $numerosUsados = [];
    foreach ($codigosExistentes as $codigo) {
        if (preg_match('/\d+$/', $codigo, $matches)) {
            $numerosUsados[] = (int)$matches[0];
        }
    }
    
    // Si no hay códigos, empezar en 1
    if (empty($numerosUsados)) {
        $numero = 1;
    } else {
        // Obtener el número más alto y sumarle 1
        $numero = max($numerosUsados) + 1;
    }

    // Formatear como EQU-001, EQU-002, etc.
    return $prefijo . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
}

}
