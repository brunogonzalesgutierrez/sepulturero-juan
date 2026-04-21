<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('empleado');
        return [
            'nombre'    => 'required|string|max:100',
            'paterno'   => 'required|string|max:100',
            'materno'   => 'nullable|string|max:100',
            'ci'        => 'required|string|max:20|unique:empleados,ci,' . ($id ? $id->id : 'NULL'),
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'cargo'     => 'nullable|string|max:100',
            'estado'    => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'ci.unique'       => 'Ya existe un empleado con ese CI.',
            'nombre.required' => 'El nombre es obligatorio.',
            'paterno.required' => 'El apellido paterno es obligatorio.',
        ];
    }
}
