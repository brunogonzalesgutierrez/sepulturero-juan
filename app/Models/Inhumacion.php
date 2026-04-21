<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inhumacion extends Model
{
    use SoftDeletes;

    protected $table = 'inhumaciones'; // 👈 CLAVE

    protected $fillable = [
        'espacio_id',
        'contrato_id',
        'nombre',
        'paterno',
        'materno',
        'fecha_nacimiento',
        'fecha_defuncion',
        'fecha_inhumacion',
        'causa_muerte'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_defuncion'  => 'date',
        'fecha_inhumacion' => 'date',
    ];

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->paterno} {$this->materno}";
    }
}
