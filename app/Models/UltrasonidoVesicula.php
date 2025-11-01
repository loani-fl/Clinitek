<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltrasonidoVesicula extends Model
{
    use HasFactory;

    // Evita la pluralización automática
    protected $table = 'ultrasonido_vesicula';

    // Campos que se pueden llenar masivamente
    protected $fillable = ['ultrasonido_id', 'resultado'];

    public function orden()
    {
        return $this->belongsTo(UltrasonidoOrder::class, 'ultrasonido_id');
    }
}
