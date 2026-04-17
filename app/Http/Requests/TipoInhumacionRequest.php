<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoInhumacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:100',
            'precio'        => 'required|numeric|min:0',
            'precio_base'   => 'required|numeric|min:0',
            'capacidad_max' => 'required|integer|min:1',
            'area_base'     => 'required|numeric|min:0',
            'estado'        => 'required|in:activo,inactivo',
        ];
    }
}
