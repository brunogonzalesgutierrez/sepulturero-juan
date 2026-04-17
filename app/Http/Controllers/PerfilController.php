<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('empleado');
        return view('perfil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        $user->update([
            'email'    => $request->email,
            'username' => $request->username,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual'  => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'Mínimo 8 caracteres.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        $user->update([
            'password'         => Hash::make($request->password),
            'intentos_fallidos' => 0,
            'bloqueado_hasta'  => null,
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
