<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UltrasonidoObstetricoTemprano extends Model
{
    protected $fillable = [
        'ultrasonido_id',
        'observaciones',
    ];

    public function ultrasonido()
    {
        return $this->belongsTo(Ultrasonido::class);
    }
}
