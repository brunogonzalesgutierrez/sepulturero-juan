<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $this->authorize('roles.ver');
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        #$this->authorize('roles.crear');
        $this->authorize('roles.ver');
        $permisos = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('.', $p->name)[0]);
        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        #$this->authorize('roles.crear');
        $this->authorize('roles.ver');
        $request->validate(['nombre' => 'required|string|max:50|unique:roles,name']);

        $rol = Role::create(['name' => $request->nombre]);
        if ($request->permisos) {
            $rol->syncPermissions($request->permisos);
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        #$this->authorize('roles.editar');
        $this->authorize('roles.ver');
        $permisos       = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('.', $p->name)[0]);
        $permisosActivos = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permisos', 'permisosActivos'));
    }

    public function update(Request $request, Role $role)
    {
        #$this->authorize('roles.editar');
        $this->authorize('roles.ver');

        if (in_array($role->name, ['Administrador', 'Cajero', 'Operador', 'Supervisor'])) {
            // Solo actualizar permisos, no el nombre de roles base
            $role->syncPermissions($request->permisos ?? []);
            return redirect()->route('roles.index')->with('success', 'Permisos actualizados.');
        }

        $request->validate(['nombre' => 'required|string|max:50|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->nombre]);
        $role->syncPermissions($request->permisos ?? []);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        #$this->authorize('roles.eliminar');
        $this->authorize('roles.ver');

        if (in_array($role->name, ['Administrador', 'Cajero', 'Operador', 'Supervisor'])) {
            return back()->with('error', 'No se pueden eliminar los roles del sistema.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene usuarios asignados.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado.');
    }
}
