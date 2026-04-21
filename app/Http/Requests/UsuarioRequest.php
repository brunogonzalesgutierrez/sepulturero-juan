<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('usuario');
        return [
            'empleado_id' => 'required|exists:empleados,id',
            'username'    => 'required|string|max:50|unique:users,username,' . ($id ? $id->id : 'NULL'),
            'email'       => 'required|email|max:150|unique:users,email,' . ($id ? $id->id : 'NULL'),
            'password'    => $id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'estado'      => 'required|in:activo,inactivo',
            'role'        => 'required|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique'       => 'Ese nombre de usuario ya está en uso.',
            'email.unique'          => 'Ese correo ya está registrado.',
            'password.confirmed'    => 'Las contraseñas no coinciden.',
            'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'empleado_id.exists'    => 'El empleado seleccionado no existe.',
            'role.exists'           => 'El rol seleccionado no es válido.',
        ];
    }
}
