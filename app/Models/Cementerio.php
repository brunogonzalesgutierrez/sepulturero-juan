<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cementerio extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'localizacion', 'estado', 'espacio_disponible', 'tipo_cementerio'];

    public function espacios()
    {
        return $this->hasMany(Espacio::class);
    }
}
