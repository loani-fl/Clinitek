<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltrasonidoOvarico extends Model
{
    use HasFactory;

    protected $table = 'ultrasonido_ovarico';
    protected $fillable = ['ultrasonido_id'];

    public function orden()
    {
        return $this->belongsTo(UltrasonidoOrder::class);
    }
}
