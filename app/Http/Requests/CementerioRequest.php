<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CementerioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => 'required|string|max:150',
            'localizacion'     => 'required|string|max:255',
            'tipo_cementerio'  => 'required|string|max:100',
            'espacio_total' => 'required|integer|min:0',
            'estado'           => 'required|in:activo,inactivo',
        ];
    }
}
