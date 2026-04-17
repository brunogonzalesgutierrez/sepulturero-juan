<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    protected $fillable = ['ci', 'nombre', 'paterno', 'materno', 'direccion', 'telefono', 'correo', 'estado'];

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->paterno} {$this->materno}";
    }
}
