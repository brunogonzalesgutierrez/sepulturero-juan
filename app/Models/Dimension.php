<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $fillable = ['ancho', 'largo', 'area'];

    public function espacios()
    {
        return $this->hasMany(Espacio::class);
    }
}
