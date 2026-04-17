<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('cliente');
        return [
            'ci'        => 'required|string|max:20|unique:clientes,ci,' . ($id ? $id->id : 'NULL'),
            'nombre'    => 'required|string|max:100',
            'paterno'   => 'required|string|max:100',
            'materno'   => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'correo'    => 'nullable|email|max:150',
            'estado'    => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'ci.required'     => 'El CI es obligatorio.',
            'ci.unique'       => 'Ya existe un cliente con ese CI.',
            'nombre.required' => 'El nombre es obligatorio.',
            'paterno.required' => 'El apellido paterno es obligatorio.',
            'correo.email'    => 'El correo no tiene formato válido.',
        ];
    }
}
