<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cementerio extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    protected $fillable = ['nombre', 'localizacion', 'estado', 'espacio_total', 'tipo_cementerio'];
=======
    protected $fillable = ['nombre', 'localizacion', 'estado', 'espacio_disponible', 'tipo_cementerio'];
>>>>>>> 665fe70f9df4c506ced3c6beb45900d4c0698f0c

    public function espacios()
    {
        return $this->hasMany(Espacio::class);
    }
}
