<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ultrasonido extends Model
{
    protected $fillable = ['paciente_id', 'fecha', 'total'];

    public function paciente() {
        return $this->belongsTo(Paciente::class);
    }

    public function higado() {
        return $this->hasOne(UltrasonidoHigado::class);
    }
    public function vesicula() {
        return $this->hasOne(UltrasonidoVesicula::class);
    }
    public function bazo() {
        return $this->hasOne(UltrasonidoBazo::class);
    }
    public function vejiga() {
        return $this->hasOne(UltrasonidoVejiga::class);
    }
    public function ovarico() {
        return $this->hasOne(UltrasonidoOvarico::class);
    }
    public function utero() {
        return $this->hasOne(UltrasonidoUtero::class);
    }
    public function tiroides() {
        return $this->hasOne(UltrasonidoTiroides::class);
    }
}
