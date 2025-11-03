<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UltrasonidoDIU extends Model
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
