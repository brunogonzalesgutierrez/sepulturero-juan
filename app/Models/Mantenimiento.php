<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mantenimiento extends Model
{
    use SoftDeletes;
    protected $fillable = ['espacio_id', 'descripcion', 'precio', 'tipo', 'estado', 'fecha_inicio', 'fecha_fin'];
    protected $casts = ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }
}
