<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Http\Requests\UsuarioRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('usuarios.ver');

        $query = User::with(['empleado', 'roles']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('username', 'like', "%$b%")
                    ->orWhere('email', 'like', "%$b%")
                    ->orWhereHas(
                        'empleado',
                        fn($e) =>
                        $e->where('nombre', 'like', "%$b%")
                            ->orWhere('paterno', 'like', "%$b%")
                    );
            });
        }

        if ($request->filled('rol')) {
            $query->role($request->rol);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $roles    = Role::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        $this->authorize('usuarios.crear');
        $roles     = Role::orderBy('name')->get();
        $empleados = Empleado::where('estado', 'activo')
            ->whereDoesntHave('usuario')
            ->orderBy('nombre')
            ->get();
        return view('usuarios.create', compact('roles', 'empleados'));
    }

    public function store(UsuarioRequest $request)
    {
        $this->authorize('usuarios.crear');

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $usuario = User::create([
            'empleado_id' => $data['empleado_id'],
            'username'    => $data['username'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'estado'      => $data['estado'],
        ]);

        $usuario->syncRoles([$data['role']]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $usuario)
    {
        $this->authorize('usuarios.ver');
        $usuario->load(['empleado', 'roles']);
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        $this->authorize('usuarios.editar');
        $roles     = Role::orderBy('name')->get();
        $empleados = Empleado::where('estado', 'activo')
            ->where(function ($q) use ($usuario) {
                $q->whereDoesntHave('usuario')
                    ->orWhereHas('usuario', fn($u) => $u->where('id', $usuario->id));
            })
            ->orderBy('nombre')
            ->get();
        return view('usuarios.edit', compact('usuario', 'roles', 'empleados'));
    }

    public function update(UsuarioRequest $request, User $usuario)
    {
        $this->authorize('usuarios.editar');

        $data = $request->validated();

        $updateData = [
            'empleado_id' => $data['empleado_id'],
            'username'    => $data['username'],
            'email'       => $data['email'],
            'estado'      => $data['estado'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
            // Resetear bloqueo si admin cambia contraseña
            $updateData['intentos_fallidos'] = 0;
            $updateData['bloqueado_hasta']   = null;
        }

        $usuario->update($updateData);
        $usuario->syncRoles([$data['role']]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $this->authorize('usuarios.eliminar');

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
