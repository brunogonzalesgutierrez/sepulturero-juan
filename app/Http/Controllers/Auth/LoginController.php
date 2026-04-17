<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\BitacoraService;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';
    protected int $maxIntentos = 5;
    protected int $minutosBloqueado = 30;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(): string
    {
        return 'username';
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function authenticated(Request $request, $user): void
    {
        // Resetear intentos al loguearse bien
        $user->update(['intentos_fallidos' => 0, 'bloqueado_hasta' => null]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if ($user) {
            $intentos = $user->intentos_fallidos + 1;

            if ($intentos >= $this->maxIntentos) {
                $user->update([
                    'intentos_fallidos' => $intentos,
                    'bloqueado_hasta'   => now()->addMinutes($this->minutosBloqueado),
                    'estado'            => 'inactivo',
                ]);

                return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        $this->username() => "Cuenta bloqueada por {$this->minutosBloqueado} minutos por múltiples intentos fallidos.",
                    ]);
            }

            $user->update(['intentos_fallidos' => $intentos]);

            $restantes = $this->maxIntentos - $intentos;
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => "Credenciales incorrectas. {$restantes} intento(s) restante(s).",
                ]);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([$this->username() => 'Usuario no encontrado.']);
    }

    protected function credentials(Request $request): array
    {
        $user = User::where('username', $request->username)->first();

        // Verificar bloqueo
        if ($user && $user->bloqueado_hasta && $user->bloqueado_hasta->isFuture()) {
            $minutos = now()->diffInMinutes($user->bloqueado_hasta);
            abort(403, "Cuenta bloqueada. Intente en {$minutos} minuto(s).");
        }

        // Verificar estado
        if ($user && $user->estado === 'inactivo' && (!$user->bloqueado_hasta || $user->bloqueado_hasta->isPast())) {
            // Desbloquear si ya pasó el tiempo
            $user->update(['estado' => 'activo', 'bloqueado_hasta' => null]);
        }

        return [
            'username' => $request->username,
            'password' => $request->password,
            'estado'   => 'activo',
        ];
    }
}
