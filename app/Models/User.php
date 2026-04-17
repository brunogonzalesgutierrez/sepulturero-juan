<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $fillable = ['empleado_id', 'username', 'email', 'password', 'estado', 'intentos_fallidos', 'bloqueado_hasta'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'bloqueado_hasta' => 'datetime',
        'password'        => 'hashed',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Para login por username en vez de email
    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
