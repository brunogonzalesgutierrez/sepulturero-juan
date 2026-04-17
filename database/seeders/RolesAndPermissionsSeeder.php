<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            // Usuarios
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            // Empleados
            'empleados.ver',
            'empleados.crear',
            'empleados.editar',
            'empleados.eliminar',
            // Clientes
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',
            // Espacios
            'espacios.ver',
            'espacios.crear',
            'espacios.editar',
            'espacios.eliminar',
            // Cementerios
            'cementerios.ver',
            'cementerios.crear',
            'cementerios.editar',
            'cementerios.eliminar',
            // Ventas
            'ventas.ver',
            'ventas.crear',
            'ventas.editar',
            'ventas.eliminar',
            // Pagos
            'pagos.ver',
            'pagos.crear',
            'pagos.editar',
            // Contratos
            'contratos.ver',
            'contratos.crear',
            'contratos.editar',
            // Inhumaciones
            'inhumaciones.ver',
            'inhumaciones.crear',
            'inhumaciones.editar',
            'inhumaciones.eliminar',
            // Mantenimientos
            'mantenimientos.ver',
            'mantenimientos.crear',
            'mantenimientos.editar',
            'mantenimientos.eliminar',
            // Reportes
            'reportes.ver',
            'reportes.exportar',
            // Bitácora
            'bitacora.ver',
            // Roles y permisos
            'roles.ver',
            'roles.crear',
            'roles.editar',
            'roles.eliminar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // ADMINISTRADOR — acceso total
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $admin->givePermissionTo(Permission::all());

        // CAJERO — ventas, pagos, clientes, contratos
        $cajero = Role::firstOrCreate(['name' => 'Cajero']);
        $cajero->givePermissionTo([
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'ventas.ver',
            'ventas.crear',
            'pagos.ver',
            'pagos.crear',
            'pagos.editar',
            'contratos.ver',
            'contratos.crear',
            'contratos.editar',
            'espacios.ver',
            'reportes.ver',
            'reportes.exportar',
        ]);

        // OPERADOR — espacios, inhumaciones, mantenimiento
        $operador = Role::firstOrCreate(['name' => 'Operador']);
        $operador->givePermissionTo([
            'espacios.ver',
            'espacios.crear',
            'espacios.editar',
            'cementerios.ver',
            'inhumaciones.ver',
            'inhumaciones.crear',
            'inhumaciones.editar',
            'mantenimientos.ver',
            'mantenimientos.crear',
            'mantenimientos.editar',
            'clientes.ver',
            'contratos.ver',
        ]);

        // SUPERVISOR — solo lectura y reportes
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisor->givePermissionTo([
            'usuarios.ver',
            'empleados.ver',
            'clientes.ver',
            'espacios.ver',
            'cementerios.ver',
            'ventas.ver',
            'pagos.ver',
            'contratos.ver',
            'inhumaciones.ver',
            'mantenimientos.ver',
            'reportes.ver',
            'reportes.exportar',
            'bitacora.ver',
        ]);
    }
}
