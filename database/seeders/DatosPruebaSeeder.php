<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Cementerio;
use App\Models\Espacio;
use App\Models\TipoInhumacion;
use App\Models\TipoMantenimiento;
use App\Models\Mantenimiento;
use App\Models\Bitacora;
use Carbon\Carbon;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // ────────────────────────────────────────────────
        // 1. PERMISOS
        // ────────────────────────────────────────────────
        $permisos = [
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
            'empleados.ver', 'empleados.crear', 'empleados.editar', 'empleados.eliminar',
            'roles.ver', 'roles.crear', 'roles.editar', 'roles.eliminar',
            'cementerios.ver', 'cementerios.crear', 'cementerios.editar', 'cementerios.eliminar',
            'espacios.ver', 'espacios.crear', 'espacios.editar', 'espacios.eliminar',
            'inhumaciones.ver', 'inhumaciones.crear', 'inhumaciones.editar', 'inhumaciones.eliminar',
            'mantenimientos.ver', 'mantenimientos.crear', 'mantenimientos.editar', 'mantenimientos.eliminar',
            'clientes.ver', 'clientes.crear', 'clientes.editar', 'clientes.eliminar',
            'contratos.ver', 'contratos.crear', 'contratos.editar', 'contratos.eliminar',
            'ventas.ver', 'ventas.crear', 'ventas.editar', 'ventas.eliminar',
            'pagos.ver', 'pagos.crear', 'pagos.editar', 'pagos.eliminar',
            'reportes.ver',
            'bitacora.ver',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // ────────────────────────────────────────────────
        // 2. ROLES
        // ────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $cajero = Role::firstOrCreate(['name' => 'Cajero', 'guard_name' => 'web']);
        $cajero->syncPermissions([
            'clientes.ver', 'clientes.crear', 'clientes.editar',
            'contratos.ver', 'contratos.crear',
            'ventas.ver', 'ventas.crear',
            'pagos.ver', 'pagos.crear',
            'espacios.ver',
            'inhumaciones.ver',
            'reportes.ver',
        ]);

        $operario = Role::firstOrCreate(['name' => 'Operario', 'guard_name' => 'web']);
        $operario->syncPermissions([
            'espacios.ver', 'espacios.editar',
            'mantenimientos.ver', 'mantenimientos.crear', 'mantenimientos.editar',
            'inhumaciones.ver',
            'cementerios.ver',
        ]);

        // ────────────────────────────────────────────────
        // 3. EMPLEADOS
        // ────────────────────────────────────────────────
        $empBruno = Empleado::firstOrCreate(['ci' => '1234567'], [
            'nombre'    => 'Bruno Daniel',
            'paterno'   => 'Gonzales',
            'materno'   => 'Gutierrez',
            'direccion' => 'Av. Monseñor Rivero #123',
            'telefono'  => '77712345',
            'cargo'     => 'Administrador',
            'estado'    => 'activo',
        ]);

        $empWilliam = Empleado::firstOrCreate(['ci' => '1234568'], [
            'nombre'    => 'William',
            'paterno'   => 'Torrico',
            'materno'   => 'Jimenez',
            'direccion' => 'Av. Monseñor Rivero #124',
            'telefono'  => '77712346',
            'cargo'     => 'Administrador',
            'estado'    => 'activo',
        ]);

        $empCajero = Empleado::firstOrCreate(['ci' => '2345678'], [
            'nombre'    => 'María',
            'paterno'   => 'López',
            'materno'   => 'Suárez',
            'direccion' => 'Calle Libertad #456',
            'telefono'  => '77723456',
            'cargo'     => 'Cajera',
            'estado'    => 'activo',
        ]);

        $empCajero2 = Empleado::firstOrCreate(['ci' => '2345679'], [
            'nombre'    => 'Rodrigo',
            'paterno'   => 'Varela',
            'materno'   => 'Quiroga',
            'direccion' => 'Av. Alemana #220',
            'telefono'  => '77723499',
            'cargo'     => 'Cajero',
            'estado'    => 'activo',
        ]);

        $empOperario = Empleado::firstOrCreate(['ci' => '3456789'], [
            'nombre'    => 'Carlos',
            'paterno'   => 'Ríos',
            'materno'   => 'Mamani',
            'direccion' => 'Barrio Las Palmas #789',
            'telefono'  => '77734567',
            'cargo'     => 'Operario',
            'estado'    => 'activo',
        ]);

        $empOperario2 = Empleado::firstOrCreate(['ci' => '3456790'], [
            'nombre'    => 'Miguel',
            'paterno'   => 'Torrez',
            'materno'   => 'Peña',
            'direccion' => 'Barrio Equipetrol #12',
            'telefono'  => '77734599',
            'cargo'     => 'Operario',
            'estado'    => 'activo',
        ]);

        // ────────────────────────────────────────────────
        // 4. USUARIOS
        // ────────────────────────────────────────────────
        $userBruno = User::firstOrCreate(['username' => 'bruno'], [
            'empleado_id' => $empBruno->id,
            'email'       => 'bruno@sepulturerojuan.xyz',
            'password'    => Hash::make('Bruno1234!'),
            'estado'      => 'activo',
        ]);
        $userBruno->syncRoles([$admin]);

        $userWilliam = User::firstOrCreate(['username' => 'william'], [
            'empleado_id' => $empWilliam->id,
            'email'       => 'william@sepulturerojuan.xyz',
            'password'    => Hash::make('William1234!'),
            'estado'      => 'activo',
        ]);
        $userWilliam->syncRoles([$admin]);

        $userCajero = User::firstOrCreate(['username' => 'cajero'], [
            'empleado_id' => $empCajero->id,
            'email'       => 'cajero@sepulturerojuan.xyz',
            'password'    => Hash::make('cajero1234!'),
            'estado'      => 'activo',
        ]);
        $userCajero->syncRoles([$cajero]);

        $userRodrigo = User::firstOrCreate(['username' => 'rodrigo'], [
            'empleado_id' => $empCajero2->id,
            'email'       => 'rodrigo@sepulturerojuan.xyz',
            'password'    => Hash::make('Rodrigo1234!'),
            'estado'      => 'activo',
        ]);
        $userRodrigo->syncRoles([$cajero]);

        $userDennis = User::firstOrCreate(['username' => 'dennis'], [
            'empleado_id' => $empOperario->id,
            'email'       => 'dennis@sepulturerojuan.xyz',
            'password'    => Hash::make('Dennis1234!'),
            'estado'      => 'activo',
        ]);
        $userDennis->syncRoles([$operario]);

        $userMiguel = User::firstOrCreate(['username' => 'miguel'], [
            'empleado_id' => $empOperario2->id,
            'email'       => 'miguel@sepulturerojuan.xyz',
            'password'    => Hash::make('Miguel1234!'),
            'estado'      => 'activo',
        ]);
        $userMiguel->syncRoles([$operario]);

        // ────────────────────────────────────────────────
        // 5. CLIENTES (25 clientes bolivianos)
        // ────────────────────────────────────────────────
        $clientesData = [
            ['ci' => '4567890', 'nombre' => 'Roberto',   'paterno' => 'Flores',     'materno' => 'Vaca',      'telefono' => '77745678', 'correo' => 'roberto.flores@mail.com'],
            ['ci' => '5678901', 'nombre' => 'Ana',        'paterno' => 'Gutierrez',  'materno' => 'Torrez',    'telefono' => '77756789', 'correo' => 'ana.gutierrez@mail.com'],
            ['ci' => '6789012', 'nombre' => 'Luis',       'paterno' => 'Vargas',     'materno' => 'Cortez',    'telefono' => '77767890', 'correo' => 'luis.vargas@mail.com'],
            ['ci' => '7890123', 'nombre' => 'Carmen',     'paterno' => 'Mendoza',    'materno' => 'Suárez',    'telefono' => '77778901', 'correo' => 'carmen.mendoza@mail.com'],
            ['ci' => '8901234', 'nombre' => 'Jorge',      'paterno' => 'Chávez',     'materno' => 'Rojas',     'telefono' => '77789012', 'correo' => 'jorge.chavez@mail.com'],
            ['ci' => '9012345', 'nombre' => 'Patricia',   'paterno' => 'Mamani',     'materno' => 'Quispe',    'telefono' => '77790123', 'correo' => 'patricia.mamani@mail.com'],
            ['ci' => '9123456', 'nombre' => 'Fernando',   'paterno' => 'Sandoval',   'materno' => 'Pedraza',   'telefono' => '77791234', 'correo' => 'fernando.sandoval@mail.com'],
            ['ci' => '9234567', 'nombre' => 'Graciela',   'paterno' => 'Torrico',    'materno' => 'Antelo',    'telefono' => '77792345', 'correo' => 'graciela.torrico@mail.com'],
            ['ci' => '9345678', 'nombre' => 'Marcelo',    'paterno' => 'Herbas',     'materno' => 'Cabrera',   'telefono' => '77793456', 'correo' => 'marcelo.herbas@mail.com'],
            ['ci' => '9456789', 'nombre' => 'Valentina',  'paterno' => 'Aguilera',   'materno' => 'Montaño',   'telefono' => '77794567', 'correo' => 'valentina.aguilera@mail.com'],
            ['ci' => '9567890', 'nombre' => 'Raúl',       'paterno' => 'Zambrana',   'materno' => 'Peña',      'telefono' => '77795678', 'correo' => 'raul.zambrana@mail.com'],
            ['ci' => '9678901', 'nombre' => 'Silvia',     'paterno' => 'Camacho',    'materno' => 'Ríos',      'telefono' => '77796789', 'correo' => 'silvia.camacho@mail.com'],
            ['ci' => '9789012', 'nombre' => 'Héctor',     'paterno' => 'Villalobos', 'materno' => 'Cruz',      'telefono' => '77797890', 'correo' => 'hector.villalobos@mail.com'],
            ['ci' => '9890123', 'nombre' => 'Lorena',     'paterno' => 'Orellana',   'materno' => 'Mostacedo', 'telefono' => '77798901', 'correo' => 'lorena.orellana@mail.com'],
            ['ci' => '9901234', 'nombre' => 'Diego',      'paterno' => 'Balcázar',   'materno' => 'Vásquez',   'telefono' => '77799012', 'correo' => 'diego.balcazar@mail.com'],
            ['ci' => '9901235', 'nombre' => 'Mónica',     'paterno' => 'Suárez',     'materno' => 'Hinojosa',  'telefono' => '77799013', 'correo' => 'monica.suarez@mail.com'],
            ['ci' => '9901236', 'nombre' => 'Álvaro',     'paterno' => 'Pereira',    'materno' => 'Nogales',   'telefono' => '77799014', 'correo' => 'alvaro.pereira@mail.com'],
            ['ci' => '9901237', 'nombre' => 'Claudia',    'paterno' => 'Terceros',   'materno' => 'Melgar',    'telefono' => '77799015', 'correo' => 'claudia.terceros@mail.com'],
            ['ci' => '9901238', 'nombre' => 'Ernesto',    'paterno' => 'Becerra',    'materno' => 'Salazar',   'telefono' => '77799016', 'correo' => 'ernesto.becerra@mail.com'],
            ['ci' => '9901239', 'nombre' => 'Isabel',     'paterno' => 'Quiroga',    'materno' => 'Arancibia', 'telefono' => '77799017', 'correo' => 'isabel.quiroga@mail.com'],
            ['ci' => '9901240', 'nombre' => 'Pablo',      'paterno' => 'Antezana',   'materno' => 'Torrez',    'telefono' => '77799018', 'correo' => 'pablo.antezana@mail.com'],
            ['ci' => '9901241', 'nombre' => 'Rosa',       'paterno' => 'Cuellar',    'materno' => 'Villca',    'telefono' => '77799019', 'correo' => 'rosa.cuellar@mail.com'],
            ['ci' => '9901242', 'nombre' => 'Gonzalo',    'paterno' => 'Morales',    'materno' => 'Choque',    'telefono' => '77799020', 'correo' => 'gonzalo.morales@mail.com'],
            ['ci' => '9901243', 'nombre' => 'Teresa',     'paterno' => 'Añez',       'materno' => 'Rivero',    'telefono' => '77799021', 'correo' => 'teresa.anez@mail.com'],
            ['ci' => '9901244', 'nombre' => 'Sebastián',  'paterno' => 'Montero',    'materno' => 'Delgado',   'telefono' => '77799022', 'correo' => 'sebastian.montero@mail.com'],
        ];

        $clientes = [];
        foreach ($clientesData as $c) {
            $clientes[] = Cliente::firstOrCreate(['ci' => $c['ci']], array_merge($c, [
                'direccion' => 'Santa Cruz de la Sierra',
                'estado'    => 'activo',
            ]));
        }

        // ────────────────────────────────────────────────
        // 6. CEMENTERIOS
        // ────────────────────────────────────────────────
        $cementerio = Cementerio::firstOrCreate(['nombre' => 'Cementerio Municipal San Juan'], [
            'localizacion'    => 'Av. Cañoto s/n, Santa Cruz de la Sierra',
            'estado'          => 'activo',
            'espacio_total'   => 500,
            'tipo_cementerio' => 'Municipal',
        ]);

        $cementerio2 = Cementerio::firstOrCreate(['nombre' => 'Jardines del Recuerdo'], [
            'localizacion'    => 'Carretera al Norte Km 5, Santa Cruz de la Sierra',
            'estado'          => 'activo',
            'espacio_total'   => 300,
            'tipo_cementerio' => 'Privado',
        ]);

        $cementerio3 = Cementerio::firstOrCreate(['nombre' => 'Parque Memorial El Cristo'], [
            'localizacion'    => 'Av. Cristo Redentor Km 3, Santa Cruz de la Sierra',
            'estado'          => 'activo',
            'espacio_total'   => 200,
            'tipo_cementerio' => 'Privado',
        ]);

        // ────────────────────────────────────────────────
        // 7. TIPOS DE INHUMACIÓN
        // Nicho | Mausoleo | Lote | Individual
        // ────────────────────────────────────────────────
        $tipoNicho = TipoInhumacion::firstOrCreate(['nombre' => 'Nicho'], [
            'precio'        => 500.00,
            'precio_m2'     => 1400.00,
            'capacidad_max' => 1,
            'estado'        => 'activo',
            'area_base'     => 2.50,
        ]);

        $tipoMausoleo = TipoInhumacion::firstOrCreate(['nombre' => 'Mausoleo'], [
            'precio'        => 2500.00,
            'precio_m2'     => 1200.00,
            'capacidad_max' => 12,
            'estado'        => 'activo',
            'area_base'     => 25.00,
        ]);

        $tipoLote = TipoInhumacion::firstOrCreate(['nombre' => 'Lote'], [
            'precio'        => 800.00,
            'precio_m2'     => 900.00,
            'capacidad_max' => 4,
            'estado'        => 'activo',
            'area_base'     => 9.00,
        ]);

        $tipoIndividual = TipoInhumacion::firstOrCreate(['nombre' => 'Individual'], [
            'precio'        => 600.00,
            'precio_m2'     => 833.00,
            'capacidad_max' => 1,
            'estado'        => 'activo',
            'area_base'     => 6.00,
        ]);

        // ────────────────────────────────────────────────
        // 8. TIPOS DE MANTENIMIENTO
        // ────────────────────────────────────────────────
        $tipoLimpieza   = TipoMantenimiento::firstOrCreate(['nombre' => 'Limpieza'],   ['descripcion' => 'Limpieza general del espacio funerario',               'precio_base' => 50.00]);
        $tipoReparacion = TipoMantenimiento::firstOrCreate(['nombre' => 'Reparación'], ['descripcion' => 'Reparación de daños estructurales (grietas, humedad)',  'precio_base' => 200.00]);
        $tipoPintura    = TipoMantenimiento::firstOrCreate(['nombre' => 'Pintura'],    ['descripcion' => 'Pintura y mantenimiento estético',                      'precio_base' => 150.00]);
        $tipoJardineria = TipoMantenimiento::firstOrCreate(['nombre' => 'Jardinería'], ['descripcion' => 'Mantenimiento de áreas verdes',                         'precio_base' => 80.00]);
        $tipoRenovacion = TipoMantenimiento::firstOrCreate(['nombre' => 'Renovación'], ['descripcion' => 'Renovación completa del espacio',                       'precio_base' => 500.00]);
        TipoMantenimiento::firstOrCreate(['nombre' => 'Otro'], ['descripcion' => 'Otros tipos de mantenimiento', 'precio_base' => 100.00]);

        // ────────────────────────────────────────────────
        // 9. ESPACIOS (30 espacios en 3 cementerios)
        // [cementerio_id, tipo_id, ancho, largo, seccion, numero, calle, fila]
        // ────────────────────────────────────────────────
        $espaciosData = [
            // Cementerio Municipal San Juan — Sección A (Nichos)
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '1', 'Calle 1', '1'],
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '2', 'Calle 1', '1'],
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '3', 'Calle 1', '2'],
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '4', 'Calle 1', '2'],
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '5', 'Calle 1', '3'],
            [$cementerio->id, $tipoNicho->id,      1.00, 2.50, 'A', '6', 'Calle 1', '3'],
            // Cementerio Municipal San Juan — Sección B (Mausoleos)
            [$cementerio->id, $tipoMausoleo->id,   5.00, 5.00, 'B', '1', 'Calle 2', '1'],
            [$cementerio->id, $tipoMausoleo->id,   5.00, 5.00, 'B', '2', 'Calle 2', '1'],
            [$cementerio->id, $tipoMausoleo->id,   5.00, 5.00, 'B', '3', 'Calle 2', '2'],
            [$cementerio->id, $tipoMausoleo->id,   5.00, 5.00, 'B', '4', 'Calle 2', '2'],
            // Cementerio Municipal San Juan — Sección C (Lotes)
            [$cementerio->id, $tipoLote->id,       3.00, 3.00, 'C', '1', 'Calle 3', '1'],
            [$cementerio->id, $tipoLote->id,       3.00, 3.00, 'C', '2', 'Calle 3', '1'],
            [$cementerio->id, $tipoLote->id,       3.00, 3.00, 'C', '3', 'Calle 3', '2'],
            // Jardines del Recuerdo — Sección A (Nichos)
            [$cementerio2->id, $tipoNicho->id,     1.00, 2.50, 'A', '1', 'Av. 1',   '1'],
            [$cementerio2->id, $tipoNicho->id,     1.00, 2.50, 'A', '2', 'Av. 1',   '1'],
            [$cementerio2->id, $tipoNicho->id,     1.00, 2.50, 'A', '3', 'Av. 1',   '2'],
            [$cementerio2->id, $tipoNicho->id,     1.00, 2.50, 'A', '4', 'Av. 1',   '2'],
            // Jardines del Recuerdo — Sección B (Mausoleos)
            [$cementerio2->id, $tipoMausoleo->id,  5.00, 5.00, 'B', '1', 'Av. 2',   '1'],
            [$cementerio2->id, $tipoMausoleo->id,  5.00, 5.00, 'B', '2', 'Av. 2',   '1'],
            // Jardines del Recuerdo — Sección C (Individuales)
            [$cementerio2->id, $tipoIndividual->id, 2.00, 3.00, 'C', '1', 'Av. 3',   '1'],
            [$cementerio2->id, $tipoIndividual->id, 2.00, 3.00, 'C', '2', 'Av. 3',   '1'],
            // Parque Memorial El Cristo — Sección A (Nichos)
            [$cementerio3->id, $tipoNicho->id,     1.00, 2.50, 'A', '1', 'Paseo 1', '1'],
            [$cementerio3->id, $tipoNicho->id,     1.00, 2.50, 'A', '2', 'Paseo 1', '1'],
            [$cementerio3->id, $tipoNicho->id,     1.00, 2.50, 'A', '3', 'Paseo 1', '2'],
            // Parque Memorial El Cristo — Sección B (Mausoleos)
            [$cementerio3->id, $tipoMausoleo->id,  5.00, 5.00, 'B', '1', 'Paseo 2', '1'],
            [$cementerio3->id, $tipoMausoleo->id,  5.00, 5.00, 'B', '2', 'Paseo 2', '1'],
            // Parque Memorial El Cristo — Sección C (Lotes)
            [$cementerio3->id, $tipoLote->id,      3.00, 3.00, 'C', '1', 'Paseo 3', '1'],
            [$cementerio3->id, $tipoLote->id,      3.00, 3.00, 'C', '2', 'Paseo 3', '1'],
            // Parque Memorial El Cristo — Sección D (Individuales)
            [$cementerio3->id, $tipoIndividual->id, 2.00, 3.00, 'D', '1', 'Paseo 4', '1'],
            [$cementerio3->id, $tipoIndividual->id, 2.00, 3.00, 'D', '2', 'Paseo 4', '1'],
        ];

        $espaciosCreados = [];
        foreach ($espaciosData as $e) {
            $direccionExistente = DB::table('direcciones')
                ->where('seccion', $e[4])
                ->where('numero',  $e[5])
                ->where('calle',   $e[6])
                ->where('fila',    $e[7])
                ->first();

            if ($direccionExistente) {
                $espacio = Espacio::find($direccionExistente->espacio_id);
            } else {
                $dimId = DB::table('dimensiones')->insertGetId([
                    'ancho'      => $e[2],
                    'largo'      => $e[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $espacio = Espacio::create([
                    'cementerio_id'      => $e[0],
                    'dimension_id'       => $dimId,
                    'tipo_inhumacion_id' => $e[1],
                    'estado'             => 'disponible',
                ]);

                DB::table('direcciones')->insert([
                    'espacio_id' => $espacio->id,
                    'seccion'    => $e[4],
                    'numero'     => $e[5],
                    'calle'      => $e[6],
                    'fila'       => $e[7],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $espaciosCreados[] = $espacio;
        }

        foreach ($espaciosCreados as $esp) {
            $esp->load(['dimension', 'tipoInhumacion']);
        }

        // Helper: monto base
        $calcularMonto = function (Espacio $esp): float {
            $ancho      = $esp->dimension->ancho          ?? 0;
            $largo      = $esp->dimension->largo          ?? 0;
            $precioM2   = $esp->tipoInhumacion->precio_m2 ?? 0;
            $precioFijo = $esp->tipoInhumacion->precio    ?? 0;
            return round(($ancho * $largo * $precioM2) + $precioFijo, 2);
        };

        // Helper: registrar contrato contado completo
        $crearContratoContado = function (
            Espacio $esp,
            Cliente $cliente,
            Empleado $empVendedor,
            Carbon $fecha,
            float $descuento,
            string $metodoPago,
            array $inhumado,
            string $observacion = ''
        ) use ($calcularMonto): void {
            if (DB::table('contratos')->where('espacio_id', $esp->id)->exists()) return;

            $monto = $calcularMonto($esp);

            $contrato = DB::table('contratos')->insertGetId([
                'cliente_id'      => $cliente->id,
                'espacio_id'      => $esp->id,
                'fecha_contrato'  => $fecha->format('Y-m-d'),
                'monto_base'      => $monto,
                'saldo_pendiente' => 0,
                'estado'          => 'pagado',
                'moneda'          => 'BOB',
                'observacion'     => $observacion ?: null,
                'created_at'      => $fecha,
                'updated_at'      => $fecha,
            ]);

            $esp->update(['estado' => 'ocupado']);

            $venta = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato,
                'cliente_id'   => $cliente->id,
                'empleado_id'  => $empVendedor->id,
                'fecha_venta'  => $fecha->format('Y-m-d'),
                'precio_total' => $monto - $descuento,
                'tipo_venta'   => 'contado',
                'moneda'       => 'BOB',
                'created_at'   => $fecha,
                'updated_at'   => $fecha,
            ]);

            DB::table('pago_contados')->insert([
                'venta_id'    => $venta,
                'descuento'   => $descuento,
                'metodo_pago' => $metodoPago,
                'created_at'  => $fecha,
                'updated_at'  => $fecha,
            ]);

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp->id,
                'contrato_id'      => $contrato,
                'nombre'           => $inhumado['nombre'],
                'paterno'          => $inhumado['paterno'],
                'materno'          => $inhumado['materno'],
                'fecha_nacimiento' => $inhumado['nacimiento'],
                'fecha_defuncion'  => $inhumado['defuncion'],
                'fecha_inhumacion' => $fecha->format('Y-m-d'),
                'causa_muerte'     => $inhumado['causa'],
                'created_at'       => $fecha,
                'updated_at'       => $fecha,
            ]);

            $nombreCliente = $cliente->nombre . ' ' . $cliente->paterno;
            Bitacora::create([
                'empleado_id'    => $empVendedor->id,
                'fecha_hora'     => $fecha,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato,
                'transaccion'    => 'Contrato #' . $contrato . ' registrado. Cliente: ' . $nombreCliente
                    . '. Espacio #' . $esp->id . '. Pago contado (' . $metodoPago . '). Monto: ' . ($monto - $descuento) . ' BOB.'
                    . ($descuento > 0 ? ' Descuento: ' . $descuento . ' BOB.' : ''),
            ]);
        };

        // Helper: registrar contrato crédito
        $crearContratoCredito = function (
            Espacio $esp,
            Cliente $cliente,
            Empleado $empVendedor,
            Carbon $fechaContrato,
            float $interes,
            int $nroCuotas,
            string $frecuencia,
            array $fechasVencimiento,
            int $cuotasPagadas,
            array $inhumado,
            string $observacion = ''
        ) use ($calcularMonto): void {
            if (DB::table('contratos')->where('espacio_id', $esp->id)->exists()) return;

            $monto            = $calcularMonto($esp);
            $montoConInteres  = round($monto * (1 + $interes / 100), 2);
            $montoCuota       = round($montoConInteres / $nroCuotas, 2);
            $saldoPendiente   = round($montoCuota * ($nroCuotas - $cuotasPagadas), 2);
            $estadoContrato   = $cuotasPagadas >= $nroCuotas ? 'pagado' : 'activo';

            $contrato = DB::table('contratos')->insertGetId([
                'cliente_id'      => $cliente->id,
                'espacio_id'      => $esp->id,
                'fecha_contrato'  => $fechaContrato->format('Y-m-d'),
                'monto_base'      => $monto,
                'saldo_pendiente' => $saldoPendiente,
                'estado'          => $estadoContrato,
                'moneda'          => 'BOB',
                'observacion'     => $observacion ?: null,
                'created_at'      => $fechaContrato,
                'updated_at'      => $fechaContrato,
            ]);

            $esp->update(['estado' => 'ocupado']);

            $venta = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato,
                'cliente_id'   => $cliente->id,
                'empleado_id'  => $empVendedor->id,
                'fecha_venta'  => $fechaContrato->format('Y-m-d'),
                'precio_total' => $montoConInteres,
                'tipo_venta'   => 'credito',
                'moneda'       => 'BOB',
                'created_at'   => $fechaContrato,
                'updated_at'   => $fechaContrato,
            ]);

            $pagoCredito = DB::table('pago_creditos')->insertGetId([
                'venta_id'      => $venta,
                'interes'       => $interes,
                'monto_inicial' => $montoConInteres,
                'created_at'    => $fechaContrato,
                'updated_at'    => $fechaContrato,
            ]);

            $planPago = DB::table('plan_pagos')->insertGetId([
                'pago_credito_id' => $pagoCredito,
                'fecha_inicio'    => $fechaContrato->format('Y-m-d'),
                'fecha_fin'       => end($fechasVencimiento)->format('Y-m-d'),
                'frecuencia'      => $frecuencia,
                'monto'           => $montoCuota,
                'interes_anual'   => $interes,
                'created_at'      => $fechaContrato,
                'updated_at'      => $fechaContrato,
            ]);
            reset($fechasVencimiento);

            $divisorInteres = $frecuencia === 'quincenal' ? 24 : 12;
            $saldoCapital   = $monto;
            $metodosPago    = ['transferencia', 'qr', 'efectivo', 'transferencia', 'efectivo', 'qr'];

            foreach ($fechasVencimiento as $idx => $fechaVenc) {
                $nroCuota     = $idx + 1;
                $esPagada     = $nroCuota <= $cuotasPagadas;
                $interesC     = round($saldoCapital * ($interes / 100) / $divisorInteres, 2);
                $capitalC     = round($montoCuota - $interesC, 2);
                $saldoCapital = max(0, round($saldoCapital - $capitalC, 2));

                $cuotaId = DB::table('cuotas')->insertGetId([
                    'plan_pago_id'      => $planPago,
                    'nro_cuota'         => $nroCuota,
                    'estado'            => $esPagada ? 'pagada' : 'pendiente',
                    'fecha_vencimiento' => $fechaVenc->format('Y-m-d'),
                    'monto'             => $montoCuota,
                    'created_at'        => $fechaContrato,
                    'updated_at'        => $fechaContrato,
                ]);

                if ($esPagada) {
                    DB::table('pagos')->insert([
                        'cuota_id'      => $cuotaId,
                        'empleado_id'   => $empVendedor->id,
                        'fecha_pago'    => $fechaVenc->format('Y-m-d'),
                        'monto_pagado'  => $montoCuota,
                        'monto_interes' => $interesC,
                        'metodo_pago'   => $metodosPago[$idx % count($metodosPago)],
                        'comprobante'   => 'COMP-' . strtoupper(substr(md5($cuotaId . $contrato), 0, 8)),
                        'created_at'    => $fechaVenc,
                        'updated_at'    => $fechaVenc,
                    ]);

                    Bitacora::create([
                        'empleado_id'    => $empVendedor->id,
                        'fecha_hora'     => $fechaVenc,
                        'tabla_afectada' => 'pagos',
                        'nro_registro'   => (string)$cuotaId,
                        'transaccion'    => 'Pago cuota #' . $nroCuota . '/' . $nroCuotas
                            . ' contrato #' . $contrato . '. Cliente: ' . $cliente->nombre . ' ' . $cliente->paterno
                            . '. Monto: ' . $montoCuota . ' BOB. Interés: ' . $interesC . ' BOB.',
                    ]);
                }
            }

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp->id,
                'contrato_id'      => $contrato,
                'nombre'           => $inhumado['nombre'],
                'paterno'          => $inhumado['paterno'],
                'materno'          => $inhumado['materno'],
                'fecha_nacimiento' => $inhumado['nacimiento'],
                'fecha_defuncion'  => $inhumado['defuncion'],
                'fecha_inhumacion' => $fechaContrato->format('Y-m-d'),
                'causa_muerte'     => $inhumado['causa'],
                'created_at'       => $fechaContrato,
                'updated_at'       => $fechaContrato,
            ]);

            Bitacora::create([
                'empleado_id'    => $empVendedor->id,
                'fecha_hora'     => $fechaContrato,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato,
                'transaccion'    => 'Contrato crédito #' . $contrato . ' para ' . $cliente->nombre . ' ' . $cliente->paterno
                    . '. ' . $nroCuotas . ' cuotas ' . $frecuencia . 's al ' . $interes . '% anual. Total: ' . $montoConInteres . ' BOB.',
            ]);
        };

        // ────────────────────────────────────────────────
        // 10. CONTRATOS (15 contratos, fechas ≤ 19/05/2026)
        // ────────────────────────────────────────────────

        // ── C1: Contado / Nicho A-1 Munic. / dic-2025 ────────────────────────
        ($crearContratoContado)(
            $espaciosCreados[0], $clientes[0], $empCajero,
            Carbon::create(2025, 12, 3),
            0, 'efectivo',
            ['nombre'=>'Pedro','paterno'=>'Flores','materno'=>'Vaca','nacimiento'=>'1945-03-12',
             'defuncion'=>'2025-11-30','causa'=>'Paro cardíaco'],
            'Pago al contado en efectivo sin observaciones.'
        );

        // ── C2: Crédito mensual 6c / Mausoleo B-1 Munic. / ene-2026 ─────────
        ($crearContratoCredito)(
            $espaciosCreados[6], $clientes[1], $empCajero,
            Carbon::create(2026, 1, 10),
            12.00, 6, 'mensual',
            [Carbon::create(2026,2,10), Carbon::create(2026,3,10), Carbon::create(2026,4,10),
             Carbon::create(2026,5,10), Carbon::create(2026,6,10), Carbon::create(2026,7,10)],
            3,
            ['nombre'=>'Lucía','paterno'=>'Gutierrez','materno'=>'Torrez','nacimiento'=>'1938-07-20',
             'defuncion'=>'2026-01-08','causa'=>'Insuficiencia respiratoria'],
            'Crédito 6 cuotas mensuales al 12% anual.'
        );

        // ── C3: Contado con desc. / Lote C-1 Munic. / ene-2026 ──────────────
        ($crearContratoContado)(
            $espaciosCreados[10], $clientes[2], $empBruno,
            Carbon::create(2026, 1, 20),
            200, 'transferencia',
            ['nombre'=>'Ernesto','paterno'=>'Vargas','materno'=>'Cortez','nacimiento'=>'1950-11-05',
             'defuncion'=>'2026-01-19','causa'=>'Diabetes mellitus complicada'],
            'Descuento especial 200 BOB por cliente frecuente.'
        );

        // ── C4: Crédito quincenal 4c / Nicho A-1 Jardines / feb-2026 ─────────
        ($crearContratoCredito)(
            $espaciosCreados[13], $clientes[3], $empCajero,
            Carbon::create(2026, 2, 1),
            15.00, 4, 'quincenal',
            [Carbon::create(2026,2,15), Carbon::create(2026,2,28),
             Carbon::create(2026,3,15), Carbon::create(2026,3,31)],
            4,
            ['nombre'=>'Héctor','paterno'=>'Mendoza','materno'=>'Suárez','nacimiento'=>'1942-05-18',
             'defuncion'=>'2026-01-30','causa'=>'Accidente cerebrovascular'],
            'Crédito quincenal 4 cuotas. Saldado en marzo 2026.'
        );

        // ── C5: Contado QR / Nicho A-2 Munic. / feb-2026 ────────────────────
        ($crearContratoContado)(
            $espaciosCreados[1], $clientes[4], $empCajero,
            Carbon::create(2026, 2, 14),
            0, 'qr',
            ['nombre'=>'Elena','paterno'=>'Chávez','materno'=>'Rojas','nacimiento'=>'1955-02-14',
             'defuncion'=>'2026-02-13','causa'=>'Cáncer de pulmón'],
            ''
        );

        // ── C6: Crédito mensual 12c / Mausoleo B-1 Jardines / feb-2026 ───────
        ($crearContratoCredito)(
            $espaciosCreados[17], $clientes[5], $empCajero2,
            Carbon::create(2026, 2, 20),
            10.00, 12, 'mensual',
            [Carbon::create(2026,3,20),  Carbon::create(2026,4,20),  Carbon::create(2026,5,20),
             Carbon::create(2026,6,20),  Carbon::create(2026,7,20),  Carbon::create(2026,8,20),
             Carbon::create(2026,9,20),  Carbon::create(2026,10,20), Carbon::create(2026,11,20),
             Carbon::create(2026,12,20), Carbon::create(2027,1,20),  Carbon::create(2027,2,20)],
            2,
            ['nombre'=>'Ramón','paterno'=>'Mamani','materno'=>'Condori','nacimiento'=>'1940-06-15',
             'defuncion'=>'2026-02-18','causa'=>'Falla renal crónica'],
            'Crédito 12 cuotas mensuales al 10% anual.'
        );

        // ── C7: Contado efectivo / Nicho A-3 Munic. / mar-2026 ───────────────
        ($crearContratoContado)(
            $espaciosCreados[2], $clientes[6], $empCajero,
            Carbon::create(2026, 3, 5),
            0, 'efectivo',
            ['nombre'=>'Catalina','paterno'=>'Sandoval','materno'=>'Pedraza','nacimiento'=>'1933-09-22',
             'defuncion'=>'2026-03-03','causa'=>'Vejez y complicaciones cardíacas'],
            ''
        );

        // ── C8: Crédito mensual 6c / Lote C-2 Munic. / mar-2026 ─────────────
        ($crearContratoCredito)(
            $espaciosCreados[11], $clientes[7], $empCajero2,
            Carbon::create(2026, 3, 15),
            12.00, 6, 'mensual',
            [Carbon::create(2026,4,15), Carbon::create(2026,5,1),
             Carbon::create(2026,6,15), Carbon::create(2026,7,15),
             Carbon::create(2026,8,15), Carbon::create(2026,9,15)],
            2,
            ['nombre'=>'Néstor','paterno'=>'Torrico','materno'=>'Antelo','nacimiento'=>'1948-12-01',
             'defuncion'=>'2026-03-13','causa'=>'Neumonía severa'],
            'Crédito 6 cuotas mensuales al 12% anual.'
        );

        // ── C9: Contado transferencia / Nicho A-2 Jardines / mar-2026 ────────
        ($crearContratoContado)(
            $espaciosCreados[14], $clientes[8], $empBruno,
            Carbon::create(2026, 3, 22),
            100, 'transferencia',
            ['nombre'=>'Blanca','paterno'=>'Herbas','materno'=>'Cabrera','nacimiento'=>'1958-04-10',
             'defuncion'=>'2026-03-20','causa'=>'Sepsis generalizada'],
            'Descuento 100 BOB por pago inmediato.'
        );

        // ── C10: Crédito quincenal 6c / Nicho A-4 Munic. / abr-2026 ──────────
        ($crearContratoCredito)(
            $espaciosCreados[3], $clientes[9], $empCajero,
            Carbon::create(2026, 4, 1),
            15.00, 6, 'quincenal',
            [Carbon::create(2026,4,15), Carbon::create(2026,4,30), Carbon::create(2026,5,15),
             Carbon::create(2026,5,30), Carbon::create(2026,6,15), Carbon::create(2026,6,30)],
            2,
            ['nombre'=>'Alfredo','paterno'=>'Aguilera','materno'=>'Montaño','nacimiento'=>'1952-07-08',
             'defuncion'=>'2026-03-30','causa'=>'Infarto al miocardio'],
            'Crédito quincenal 6 cuotas al 15% anual.'
        );

        // ── C11: Contado QR / Mausoleo B-3 Munic. / abr-2026 ────────────────
        ($crearContratoContado)(
            $espaciosCreados[8], $clientes[10], $empCajero2,
            Carbon::create(2026, 4, 8),
            0, 'qr',
            ['nombre'=>'Dolores','paterno'=>'Zambrana','materno'=>'Peña','nacimiento'=>'1944-01-30',
             'defuncion'=>'2026-04-06','causa'=>'Cáncer de colon'],
            ''
        );

        // ── C12: Crédito mensual 6c / Nicho A-3 Jardines / abr-2026 ──────────
        ($crearContratoCredito)(
            $espaciosCreados[15], $clientes[11], $empCajero,
            Carbon::create(2026, 4, 15),
            12.00, 6, 'mensual',
            [Carbon::create(2026,5,1),  Carbon::create(2026,6,1),
             Carbon::create(2026,7,1),  Carbon::create(2026,8,1),
             Carbon::create(2026,9,1),  Carbon::create(2026,10,1)],
            1,
            ['nombre'=>'Rodolfo','paterno'=>'Camacho','materno'=>'Ríos','nacimiento'=>'1955-03-17',
             'defuncion'=>'2026-04-13','causa'=>'EPOC terminal'],
            'Crédito 6 cuotas mensuales al 12% anual.'
        );

        // ── C13: Contado efectivo / Individual C-1 Jardines / may-2026 ───────
        ($crearContratoContado)(
            $espaciosCreados[19], $clientes[12], $empCajero2,
            Carbon::create(2026, 5, 2),
            0, 'efectivo',
            ['nombre'=>'Josefina','paterno'=>'Villalobos','materno'=>'Cruz','nacimiento'=>'1936-08-25',
             'defuncion'=>'2026-04-30','causa'=>'Falla cardíaca congestiva'],
            ''
        );

        // ── C14: Contado QR / Nicho A-1 El Cristo / may-2026 ────────────────
        ($crearContratoContado)(
            $espaciosCreados[21], $clientes[13], $empCajero,
            Carbon::create(2026, 5, 12),
            150, 'qr',
            ['nombre'=>'Augusto','paterno'=>'Orellana','materno'=>'Mostacedo','nacimiento'=>'1949-11-11',
             'defuncion'=>'2026-05-10','causa'=>'Accidente de tránsito'],
            'Descuento 150 BOB por familiar ya registrado.'
        );

        // ── C15: Crédito quincenal 4c / Mausoleo B-1 El Cristo / may-2026 ────
        ($crearContratoCredito)(
            $espaciosCreados[24], $clientes[14], $empWilliam,
            Carbon::create(2026, 5, 19),
            15.00, 4, 'quincenal',
            [Carbon::create(2026,6,2),  Carbon::create(2026,6,17),
             Carbon::create(2026,7,2),  Carbon::create(2026,7,17)],
            0,
            ['nombre'=>'Ignacio','paterno'=>'Balcázar','materno'=>'Vásquez','nacimiento'=>'1943-02-28',
             'defuncion'=>'2026-05-17','causa'=>'Insuficiencia hepática'],
            'Contrato registrado hoy. Primer vencimiento 02/06/2026.'
        );

        // ────────────────────────────────────────────────
        // 11. MANTENIMIENTOS (12 registros)
        // ────────────────────────────────────────────────
        $mantenimientosData = [
            [$espaciosCreados[0]->id,  $tipoLimpieza->id,    60.00,  'completado', 'Limpieza mensual nicho A-1 Munic.',               $empOperario->id,  Carbon::create(2025,12,10)],
            [$espaciosCreados[1]->id,  $tipoReparacion->id, 230.00,  'completado', 'Reparación grieta lateral nicho A-2 Munic.',       $empOperario->id,  Carbon::create(2026, 1,15)],
            [$espaciosCreados[6]->id,  $tipoPintura->id,    160.00,  'completado', 'Repintado mausoleo B-1 Munic.',                    $empOperario2->id, Carbon::create(2026, 2, 5)],
            [$espaciosCreados[7]->id,  $tipoJardineria->id,  90.00,  'completado', 'Poda y limpieza área verde mausoleo B-2 Munic.',   $empOperario->id,  Carbon::create(2026, 2,20)],
            [$espaciosCreados[10]->id, $tipoLimpieza->id,    65.00,  'completado', 'Limpieza lote C-1 Munic. post-inhumación.',        $empOperario2->id, Carbon::create(2026, 3, 1)],
            [$espaciosCreados[13]->id, $tipoPintura->id,    155.00,  'completado', 'Repintado nicho A-1 Jardines.',                    $empOperario->id,  Carbon::create(2026, 3,18)],
            [$espaciosCreados[17]->id, $tipoReparacion->id, 210.00,  'completado', 'Reparación humedad mausoleo B-1 Jardines.',        $empOperario2->id, Carbon::create(2026, 4, 2)],
            [$espaciosCreados[19]->id, $tipoJardineria->id,  85.00,  'completado', 'Jardinería individual C-1 Jardines.',              $empOperario->id,  Carbon::create(2026, 4,22)],
            [$espaciosCreados[8]->id,  $tipoLimpieza->id,    70.00,  'en_proceso', 'Limpieza mausoleo B-3 Munic. post-inhumación.',    $empOperario2->id, Carbon::create(2026, 5, 8)],
            [$espaciosCreados[2]->id,  $tipoRenovacion->id, 530.00,  'en_proceso', 'Renovación completa nicho A-3 Munic.',             $empOperario->id,  Carbon::create(2026, 5,12)],
            [$espaciosCreados[21]->id, $tipoPintura->id,    145.00,  'pendiente',  'Pintura nicho A-1 El Cristo.',                     $empOperario2->id, Carbon::create(2026, 5,19)],
            [$espaciosCreados[24]->id, $tipoLimpieza->id,    75.00,  'pendiente',  'Limpieza mausoleo B-1 El Cristo pre-inhumación.',  $empOperario->id,  Carbon::create(2026, 5,19)],
        ];

        foreach ($mantenimientosData as $m) {
            /** @var Carbon $fechaMant */
            $fechaMant = $m[6];

            $yaExiste = Mantenimiento::where('espacio_id', $m[0])
                ->where('tipo_mantenimiento_id', $m[1])
                ->where('fecha_inicio', $fechaMant->format('Y-m-d'))
                ->exists();

            if (!$yaExiste) {
                $mant = Mantenimiento::create([
                    'espacio_id'            => $m[0],
                    'tipo_mantenimiento_id' => $m[1],
                    'precio'                => $m[2],
                    'estado'                => $m[3],
                    'descripcion'           => $m[4],
                    'fecha_inicio'          => $fechaMant->format('Y-m-d'),
                    'fecha_fin'             => $m[3] === 'completado'
                        ? $fechaMant->copy()->addDays(3)->format('Y-m-d')
                        : null,
                ]);

                Bitacora::create([
                    'empleado_id'    => $m[5],
                    'fecha_hora'     => $fechaMant,
                    'tabla_afectada' => 'mantenimientos',
                    'nro_registro'   => (string)$mant->id,
                    'transaccion'    => 'Mantenimiento registrado: ' . $m[4] . '. Estado: ' . $m[3] . '. Costo: ' . $m[2] . ' BOB.',
                ]);
            }
        }

        // ────────────────────────────────────────────────
        // 12. BITÁCORA — acciones administrativas históricas
        // ────────────────────────────────────────────────
        $bitacoraExtra = [
            [$empBruno->id,     Carbon::create(2025,10,1),  'cementerios',       '1', 'Registro del Cementerio Municipal San Juan. Capacidad: 500 espacios.'],
            [$empBruno->id,     Carbon::create(2025,10,1),  'cementerios',       '2', 'Registro de Jardines del Recuerdo. Capacidad: 300 espacios.'],
            [$empWilliam->id,   Carbon::create(2025,10,2),  'cementerios',       '3', 'Registro de Parque Memorial El Cristo. Capacidad: 200 espacios.'],
            [$empBruno->id,     Carbon::create(2025,10,3),  'tipo_inhumaciones', '1', 'Tipo Nicho creado. Precio/m²: 1400 BOB. Inhumación: 500 BOB.'],
            [$empBruno->id,     Carbon::create(2025,10,3),  'tipo_inhumaciones', '2', 'Tipo Mausoleo creado. Precio/m²: 1200 BOB. Inhumación: 2500 BOB.'],
            [$empWilliam->id,   Carbon::create(2025,10,3),  'tipo_inhumaciones', '3', 'Tipo Lote creado. Precio/m²: 900 BOB. Inhumación: 800 BOB.'],
            [$empWilliam->id,   Carbon::create(2025,10,3),  'tipo_inhumaciones', '4', 'Tipo Individual creado. Precio/m²: 833 BOB. Inhumación: 600 BOB.'],
            [$empBruno->id,     Carbon::create(2025,10,5),  'empleados',         '2', 'Empleada María López dada de alta como Cajera.'],
            [$empWilliam->id,   Carbon::create(2025,10,5),  'empleados',         '3', 'Empleado Rodrigo Varela dado de alta como Cajero.'],
            [$empBruno->id,     Carbon::create(2025,10,5),  'empleados',         '4', 'Empleado Carlos Ríos dado de alta como Operario.'],
            [$empWilliam->id,   Carbon::create(2025,10,5),  'empleados',         '5', 'Empleado Miguel Torrez dado de alta como Operario.'],
            [$empBruno->id,     Carbon::create(2025,10,10), 'espacios',          '1', 'Registro de 6 nichos sección A en Cementerio Municipal.'],
            [$empWilliam->id,   Carbon::create(2025,10,10), 'espacios',          '2', 'Registro de 4 mausoleos sección B en Cementerio Municipal.'],
            [$empBruno->id,     Carbon::create(2025,10,10), 'espacios',          '3', 'Registro de 3 lotes sección C en Cementerio Municipal.'],
            [$empWilliam->id,   Carbon::create(2025,10,12), 'espacios',          '4', 'Registro de 4 nichos y 2 mausoleos en Jardines del Recuerdo.'],
            [$empBruno->id,     Carbon::create(2025,10,12), 'espacios',          '5', 'Registro de 3 nichos, 2 mausoleos, 2 lotes y 2 individuales en Parque El Cristo.'],
            [$empCajero->id,    Carbon::create(2025,12,1),  'clientes',          '1', 'Cliente Roberto Flores registrado. CI: 4567890.'],
            [$empCajero->id,    Carbon::create(2025,12,15), 'clientes',          '2', 'Cliente Ana Gutierrez registrada. CI: 5678901.'],
            [$empBruno->id,     Carbon::create(2026,1,5),   'clientes',          '3', 'Cliente Luis Vargas registrado. CI: 6789012.'],
            [$empCajero->id,    Carbon::create(2026,1,20),  'clientes',          '4', 'Cliente Carmen Mendoza registrada. CI: 7890123.'],
            [$empCajero2->id,   Carbon::create(2026,2,1),   'clientes',          '5', 'Cliente Jorge Chávez registrado. CI: 8901234.'],
            [$empCajero2->id,   Carbon::create(2026,2,5),   'clientes',          '6', 'Cliente Patricia Mamani registrada. CI: 9012345.'],
            [$empCajero->id,    Carbon::create(2026,2,10),  'clientes',          '7', 'Cliente Fernando Sandoval registrado. CI: 9123456.'],
            [$empCajero->id,    Carbon::create(2026,2,18),  'clientes',          '8', 'Cliente Graciela Torrico registrada. CI: 9234567.'],
            [$empCajero2->id,   Carbon::create(2026,3,2),   'clientes',          '9', 'Cliente Marcelo Herbas registrado. CI: 9345678.'],
            [$empCajero->id,    Carbon::create(2026,3,12),  'clientes',         '10', 'Cliente Valentina Aguilera registrada. CI: 9456789.'],
            [$empCajero2->id,   Carbon::create(2026,3,20),  'clientes',         '11', 'Cliente Raúl Zambrana registrado. CI: 9567890.'],
            [$empCajero->id,    Carbon::create(2026,4,1),   'clientes',         '12', 'Cliente Silvia Camacho registrada. CI: 9678901.'],
            [$empCajero2->id,   Carbon::create(2026,4,8),   'clientes',         '13', 'Cliente Héctor Villalobos registrado. CI: 9789012.'],
            [$empCajero->id,    Carbon::create(2026,4,14),  'clientes',         '14', 'Cliente Lorena Orellana registrada. CI: 9890123.'],
            [$empWilliam->id,   Carbon::create(2026,4,20),  'clientes',         '15', 'Cliente Diego Balcázar registrado. CI: 9901234.'],
            [$empOperario->id,  Carbon::create(2026,3,25),  'espacios',          '3', 'Espacio nicho A-3 Munic. marcado para renovación.'],
            [$empOperario2->id, Carbon::create(2026,4,10),  'espacios',          '9', 'Inspección mausoleo B-3 Munic. Requiere limpieza post-ocupación.'],
            [$empBruno->id,     Carbon::create(2026,4,30),  'usuarios',          '4', 'Usuario rodrigo dado de alta. Rol: Cajero.'],
            [$empWilliam->id,   Carbon::create(2026,5,1),   'usuarios',          '5', 'Usuario miguel dado de alta. Rol: Operario.'],
            [$empBruno->id,     Carbon::create(2026,5,16),  'usuarios',          '3', 'Contraseña del usuario cajero restablecida por administrador.'],
            [$empOperario->id,  Carbon::create(2026,5,19),  'espacios',         '22', 'Espacio nicho A-1 El Cristo preparado para inhumación próxima.'],
            [$empOperario2->id, Carbon::create(2026,5,19),  'espacios',         '25', 'Espacio mausoleo B-1 El Cristo registrado para contrato del día.'],
        ];

        foreach ($bitacoraExtra as $b) {
            Bitacora::create([
                'empleado_id'    => $b[0],
                'fecha_hora'     => $b[1],
                'tabla_afectada' => $b[2],
                'nro_registro'   => $b[3],
                'transaccion'    => $b[4],
            ]);
        }

        // ────────────────────────────────────────────────
        // RESUMEN
        // ────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✅ Datos de prueba cargados correctamente.');
        $this->command->info('');
        $this->command->info('👤 Usuarios:');
        $this->command->info('   bruno    / Bruno1234!    → Administrador  (Bruno Daniel Gonzales Gutierrez)');
        $this->command->info('   william  / William1234!  → Administrador  (William Torrico Jimenez)');
        $this->command->info('   cajero   / cajero1234!   → Cajero');
        $this->command->info('   rodrigo  / Rodrigo1234!  → Cajero');
        $this->command->info('   dennis   / Dennis1234!   → Operario');
        $this->command->info('   miguel   / Miguel1234!   → Operario');
        $this->command->info('');
        $this->command->info('🏛  Cementerios: 3');
        $this->command->info('   Cementerio Municipal San Juan (Municipal)');
        $this->command->info('   Jardines del Recuerdo (Privado)');
        $this->command->info('   Parque Memorial El Cristo (Privado)');
        $this->command->info('');
        $this->command->info('🪦 Tipos de inhumación: 4');
        $this->command->info('   Nicho      — Bs. 500 + 1400/m² — cap. 1  — 2.50 m²');
        $this->command->info('   Mausoleo   — Bs. 2500 + 1200/m² — cap. 12 — 25.00 m²');
        $this->command->info('   Lote       — Bs. 800 + 900/m²  — cap. 4  — 9.00 m²');
        $this->command->info('   Individual — Bs. 600 + 833/m²  — cap. 1  — 6.00 m²');
        $this->command->info('');
        $this->command->info('👥 Clientes: 25');
        $this->command->info('🪦 Espacios: 30');
        $this->command->info('   Munic.: 6 nichos (A) | 4 mausoleos (B) | 3 lotes (C)');
        $this->command->info('   Jardines: 4 nichos (A) | 2 mausoleos (B) | 2 individuales (C)');
        $this->command->info('   El Cristo: 3 nichos (A) | 2 mausoleos (B) | 2 lotes (C) | 2 individuales (D)');
        $this->command->info('');
        $this->command->info('📋 Contratos: 15');
        $this->command->info('   C01 Roberto Flores     Nicho A-1 Munic.         Contado      03/12/2025 Pagado');
        $this->command->info('   C02 Ana Gutierrez      Mausoleo B-1 Munic.      Crédito      10/01/2026 Activo  3/6 cuotas');
        $this->command->info('   C03 Luis Vargas        Lote C-1 Munic.          Contado      20/01/2026 Pagado  (desc. 200)');
        $this->command->info('   C04 Carmen Mendoza     Nicho A-1 Jardines       Crédito      01/02/2026 Pagado  4/4 cuotas');
        $this->command->info('   C05 Jorge Chávez       Nicho A-2 Munic.         Contado QR   14/02/2026 Pagado');
        $this->command->info('   C06 Patricia Mamani    Mausoleo B-1 Jardines    Crédito      20/02/2026 Activo  2/12 cuotas');
        $this->command->info('   C07 Fernando Sandoval  Nicho A-3 Munic.         Contado      05/03/2026 Pagado');
        $this->command->info('   C08 Graciela Torrico   Lote C-2 Munic.          Crédito      15/03/2026 Activo  2/6 cuotas');
        $this->command->info('   C09 Marcelo Herbas     Nicho A-2 Jardines       Contado      22/03/2026 Pagado  (desc. 100)');
        $this->command->info('   C10 Valentina Aguilera Nicho A-4 Munic.         Crédito      01/04/2026 Activo  2/6 cuotas');
        $this->command->info('   C11 Raúl Zambrana      Mausoleo B-3 Munic.      Contado QR   08/04/2026 Pagado');
        $this->command->info('   C12 Silvia Camacho     Nicho A-3 Jardines       Crédito      15/04/2026 Activo  1/6 cuotas');
        $this->command->info('   C13 Héctor Villalobos  Individual C-1 Jardines  Contado      02/05/2026 Pagado');
        $this->command->info('   C14 Lorena Orellana    Nicho A-1 El Cristo      Contado QR   12/05/2026 Pagado  (desc. 150)');
        $this->command->info('   C15 Diego Balcázar     Mausoleo B-1 El Cristo   Crédito      19/05/2026 Activo  0/4 cuotas');
        $this->command->info('');
        $this->command->info('💰 Ventas: 8 contado | 7 crédito');
        $this->command->info('🪦 Inhumaciones: 15');
        $this->command->info('🔧 Mantenimientos: 12  (8 completados | 2 en proceso | 2 pendientes)');
        $this->command->info('📝 Registros en bitácora: ~90');
        $this->command->info('');
    }
}