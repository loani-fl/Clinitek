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

    public static function generarCodigoPorCategoria($categoria)
{
    // Normalizamos el prefijo (3 letras mayúsculas)
    $prefijo = strtoupper(substr($categoria, 0, 3));

    // Buscamos el último código que empiece con ese prefijo
    $ultimo = self::where('codigo', 'like', $prefijo . '-%')
        ->orderBy('id', 'desc')
        ->first();

    if (!$ultimo) {
        $numero = 1;
    } else {
        // Extraer el número del código (ej: MED-005 → 5)
        $numero = intval(substr($ultimo->codigo, 4)) + 1;
    }

    // Formatear como MED-001, MED-002, etc.
    return $prefijo . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
}

}
