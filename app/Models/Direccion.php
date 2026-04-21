<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direcciones';
    protected $fillable = ['espacio_id', 'seccion', 'numero', 'calle', 'fila'];

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }
}
