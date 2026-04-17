<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'paterno', 'materno', 'ci', 'direccion', 'telefono', 'cargo', 'estado'];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->paterno} {$this->materno}";
    }
}
