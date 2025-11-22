<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use HasRoles;


    protected $table = 'usuarios';

    protected $fillable = [
        'name',
        'email',
        'rol',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}

