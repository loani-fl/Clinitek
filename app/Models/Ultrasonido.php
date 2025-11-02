<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ultrasonido extends Model
{
    // Campos que se pueden asignar en masa
    protected $fillable = [
        'paciente_id',
        'fecha',
        'total',
        'descripcion',
        'medico_id',
        'examenes', // ✅ Campo JSON que guarda los ultrasonidos seleccionados
    ];

    // ✅ Convierte automáticamente el campo JSON en un array PHP
    protected $casts = [
        'examenes' => 'array',
    ];

    // --- Relaciones ---

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function imagenes()
    {
        return $this->hasMany(UltrasonidoImagen::class, 'ultrasonido_id');
    }

    public function higado()
    {
        return $this->hasOne(UltrasonidoHigado::class);
    }

    public function vesicula()
    {
        return $this->hasOne(UltrasonidoVesicula::class);
    }

    public function bazo()
    {
        return $this->hasOne(UltrasonidoBazo::class);
    }

    public function vejiga()
    {
        return $this->hasOne(UltrasonidoVejiga::class);
    }

    public function ovarico()
    {
        return $this->hasOne(UltrasonidoOvarico::class);
    }

    public function utero()
    {
        return $this->hasOne(UltrasonidoUtero::class);
    }

    public function tiroides()
    {
        return $this->hasOne(UltrasonidoTiroides::class);
    }
}
