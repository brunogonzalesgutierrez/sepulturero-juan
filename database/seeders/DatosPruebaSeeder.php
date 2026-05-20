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
        $empAdmin = Empleado::firstOrCreate(['ci' => '1234567'], [
            'nombre'    => 'Juan',
            'paterno'   => 'Pérez',
            'materno'   => 'García',
            'direccion' => 'Av. Monseñor Rivero #123',
            'telefono'  => '77712345',
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

        $empOperario = Empleado::firstOrCreate(['ci' => '3456789'], [
            'nombre'    => 'Carlos',
            'paterno'   => 'Ríos',
            'materno'   => 'Mamani',
            'direccion' => 'Barrio Las Palmas #789',
            'telefono'  => '77734567',
            'cargo'     => 'Operario',
            'estado'    => 'activo',
        ]);

        // ────────────────────────────────────────────────
        // 4. USUARIOS
        // ────────────────────────────────────────────────
        $userBruno = User::firstOrCreate(['username' => 'bruno'], [
            'empleado_id' => $empAdmin->id,
            'email'       => 'bruno@sepulturerojuan.xyz',
            'password'    => Hash::make('Bruno1234!'),
            'estado'      => 'activo',
        ]);
        $userBruno->syncRoles([$admin]);

        $userWilliam = User::firstOrCreate(['username' => 'william'], [
            'empleado_id' => $empAdmin->id,
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

        $userDennis = User::firstOrCreate(['username' => 'dennis'], [
            'empleado_id' => $empOperario->id,
            'email'       => 'dennis@sepulturerojuan.xyz',
            'password'    => Hash::make('Dennis1234!'),
            'estado'      => 'activo',
        ]);
        $userDennis->syncRoles([$operario]);

        // ────────────────────────────────────────────────
        // 5. CLIENTES
        // ────────────────────────────────────────────────
        $clientesData = [
            ['ci' => '4567890', 'nombre' => 'Roberto',   'paterno' => 'Flores',    'materno' => 'Vaca',      'telefono' => '77745678', 'correo' => 'roberto.flores@mail.com'],
            ['ci' => '5678901', 'nombre' => 'Ana',        'paterno' => 'Gutierrez', 'materno' => 'Torrez',    'telefono' => '77756789', 'correo' => 'ana.gutierrez@mail.com'],
            ['ci' => '6789012', 'nombre' => 'Luis',       'paterno' => 'Vargas',    'materno' => 'Cortez',    'telefono' => '77767890', 'correo' => 'luis.vargas@mail.com'],
            ['ci' => '7890123', 'nombre' => 'Carmen',     'paterno' => 'Mendoza',   'materno' => 'Suárez',    'telefono' => '77778901', 'correo' => 'carmen.mendoza@mail.com'],
            ['ci' => '8901234', 'nombre' => 'Jorge',      'paterno' => 'Chávez',    'materno' => 'Rojas',     'telefono' => '77789012', 'correo' => 'jorge.chavez@mail.com'],
            ['ci' => '9012345', 'nombre' => 'Patricia',   'paterno' => 'Mamani',    'materno' => 'Quispe',    'telefono' => '77790123', 'correo' => 'patricia.mamani@mail.com'],
            ['ci' => '9123456', 'nombre' => 'Fernando',   'paterno' => 'Sandoval',  'materno' => 'Pedraza',   'telefono' => '77791234', 'correo' => 'fernando.sandoval@mail.com'],
            ['ci' => '9234567', 'nombre' => 'Graciela',   'paterno' => 'Torrico',   'materno' => 'Antelo',    'telefono' => '77792345', 'correo' => 'graciela.torrico@mail.com'],
            ['ci' => '9345678', 'nombre' => 'Marcelo',    'paterno' => 'Herbas',    'materno' => 'Cabrera',   'telefono' => '77793456', 'correo' => 'marcelo.herbas@mail.com'],
            ['ci' => '9456789', 'nombre' => 'Valentina',  'paterno' => 'Aguilera',  'materno' => 'Montaño',   'telefono' => '77794567', 'correo' => 'valentina.aguilera@mail.com'],
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

        // ────────────────────────────────────────────────
        // 7. TIPOS DE INHUMACIÓN
        // ────────────────────────────────────────────────
        $tipoNicho = TipoInhumacion::firstOrCreate(['nombre' => 'Nicho'], [
            'precio'        => 500.00,
            'precio_m2'     => 1400.00,
            'capacidad_max' => 1,
            'estado'        => 'activo',
            'area_base'     => 2.50,
        ]);

        $tipoBoveda = TipoInhumacion::firstOrCreate(['nombre' => 'Bóveda'], [
            'precio'        => 800.00,
            'precio_m2'     => 888.00,
            'capacidad_max' => 4,
            'estado'        => 'activo',
            'area_base'     => 9.00,
        ]);

        $tipoTerreno = TipoInhumacion::firstOrCreate(['nombre' => 'Terreno'], [
            'precio'        => 600.00,
            'precio_m2'     => 833.00,
            'capacidad_max' => 2,
            'estado'        => 'activo',
            'area_base'     => 6.00,
        ]);

        // ────────────────────────────────────────────────
        // 8. TIPOS DE MANTENIMIENTO
        // ────────────────────────────────────────────────
        $tipoLimpieza   = TipoMantenimiento::firstOrCreate(['nombre' => 'Limpieza'],   ['descripcion' => 'Limpieza general del espacio funerario',              'precio_base' => 50.00]);
        $tipoReparacion = TipoMantenimiento::firstOrCreate(['nombre' => 'Reparación'], ['descripcion' => 'Reparación de daños estructurales (grietas, humedad)', 'precio_base' => 200.00]);
        $tipoPintura    = TipoMantenimiento::firstOrCreate(['nombre' => 'Pintura'],    ['descripcion' => 'Pintura y mantenimiento estético',                     'precio_base' => 150.00]);
        $tipoJardineria = TipoMantenimiento::firstOrCreate(['nombre' => 'Jardinería'], ['descripcion' => 'Mantenimiento de áreas verdes',                        'precio_base' => 80.00]);
        $tipoRenovacion = TipoMantenimiento::firstOrCreate(['nombre' => 'Renovación'], ['descripcion' => 'Renovación completa del espacio',                      'precio_base' => 500.00]);
        TipoMantenimiento::firstOrCreate(['nombre' => 'Otro'], ['descripcion' => 'Otros tipos de mantenimiento', 'precio_base' => 100.00]);

        // ────────────────────────────────────────────────
        // 9. ESPACIOS
        // Usamos firstOrCreate en dimensiones y direcciones
        // para evitar duplicados en re-ejecuciones.
        // ────────────────────────────────────────────────
        $espaciosData = [
            // [cementerio_id, tipo_id, ancho, largo, seccion, numero, calle, fila]
            [$cementerio->id,  $tipoNicho->id,   1.00, 2.50, 'A', '1', 'Calle 1', '1'],
            [$cementerio->id,  $tipoNicho->id,   1.00, 2.50, 'A', '2', 'Calle 1', '1'],
            [$cementerio->id,  $tipoNicho->id,   1.00, 2.50, 'A', '3', 'Calle 1', '2'],
            [$cementerio->id,  $tipoNicho->id,   1.00, 2.50, 'A', '4', 'Calle 1', '2'],
            [$cementerio->id,  $tipoBoveda->id,  3.00, 3.00, 'B', '1', 'Calle 2', '1'],
            [$cementerio->id,  $tipoBoveda->id,  3.00, 3.00, 'B', '2', 'Calle 2', '1'],
            [$cementerio->id,  $tipoTerreno->id, 2.00, 3.00, 'C', '1', 'Calle 3', '1'],
            [$cementerio->id,  $tipoTerreno->id, 2.00, 3.00, 'C', '2', 'Calle 3', '1'],
            [$cementerio2->id, $tipoNicho->id,   1.00, 2.50, 'A', '1', 'Av. 1',   '1'],
            [$cementerio2->id, $tipoNicho->id,   1.00, 2.50, 'A', '2', 'Av. 1',   '1'],
            [$cementerio2->id, $tipoBoveda->id,  3.00, 3.00, 'B', '1', 'Av. 2',   '1'],
            [$cementerio2->id, $tipoTerreno->id, 2.00, 3.00, 'C', '1', 'Av. 3',   '1'],
        ];

        $espaciosCreados = [];
        foreach ($espaciosData as $e) {
            // Buscar espacio existente por cementerio + tipo + dirección
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

        // Cargar relaciones necesarias para cálculo de montos
        foreach ($espaciosCreados as $esp) {
            $esp->load(['dimension', 'tipoInhumacion']);
        }

        // Helper: calcula monto base según dimensiones y precio del tipo
        $calcularMonto = function (Espacio $esp): float {
            $ancho      = $esp->dimension->ancho      ?? 0;
            $largo      = $esp->dimension->largo      ?? 0;
            $precioM2   = $esp->tipoInhumacion->precio_m2 ?? 0;
            $precioFijo = $esp->tipoInhumacion->precio    ?? 0;
            return round(($ancho * $largo * $precioM2) + $precioFijo, 2);
        };

        // ────────────────────────────────────────────────
        // 10. CONTRATOS + VENTAS + PAGOS + INHUMACIONES
        //
        // Línea de tiempo (referencia: 19/05/2026):
        //   Contrato 1 → 01/12/2025  (contado, pagado)
        //   Contrato 2 → 01/02/2026  (crédito 6 cuotas, 3 pagadas)
        //   Contrato 3 → 01/03/2026  (contado con descuento, pagado)
        //   Contrato 4 → 01/04/2026  (crédito quincenal 4 cuotas, 1 pagada)
        //   Contrato 5 → 05/05/2026  (contado, pagado)
        // ────────────────────────────────────────────────

        // ── CONTRATO 1: Contado, pagado completo ─────────────────────────────
        $esp1   = $espaciosCreados[0]; // Nicho A-1 Cementerio Municipal
        $monto1 = $calcularMonto($esp1);
        $fecha1 = Carbon::create(2025, 12, 1);

        if (!DB::table('contratos')->where('espacio_id', $esp1->id)->exists()) {
            $contrato1 = DB::table('contratos')->insertGetId([
                'cliente_id'      => $clientes[0]->id,
                'espacio_id'      => $esp1->id,
                'fecha_contrato'  => $fecha1->format('Y-m-d'),
                'monto_base'      => $monto1,
                'saldo_pendiente' => 0,
                'estado'          => 'pagado',
                'moneda'          => 'BOB',
                'observacion'     => 'Contrato cancelado al contado. Sin observaciones.',
                'created_at'      => $fecha1,
                'updated_at'      => $fecha1,
            ]);

            $esp1->update(['estado' => 'ocupado']);

            $venta1 = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato1,
                'cliente_id'   => $clientes[0]->id,
                'empleado_id'  => $empCajero->id,
                'fecha_venta'  => $fecha1->format('Y-m-d'),
                'precio_total' => $monto1,
                'tipo_venta'   => 'contado',
                'moneda'       => 'BOB',
                'created_at'   => $fecha1,
                'updated_at'   => $fecha1,
            ]);

            DB::table('pago_contados')->insertGetId([
                'venta_id'    => $venta1,
                'descuento'   => 0,
                'metodo_pago' => 'efectivo',
                'created_at'  => $fecha1,
                'updated_at'  => $fecha1,
            ]);

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp1->id,
                'contrato_id'      => $contrato1,
                'nombre'           => 'Pedro',
                'paterno'          => 'Flores',
                'materno'          => 'Vaca',
                'fecha_nacimiento' => '1945-03-12',
                'fecha_defuncion'  => $fecha1->copy()->subDays(3)->format('Y-m-d'),
                'fecha_inhumacion' => $fecha1->format('Y-m-d'),
                'causa_muerte'     => 'Paro cardíaco',
                'created_at'       => $fecha1,
                'updated_at'       => $fecha1,
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha1,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato1,
                'transaccion'    => 'Registro de contrato #' . $contrato1 . ' para cliente Roberto Flores. Espacio Nicho A-1. Pago al contado en efectivo por ' . $monto1 . ' BOB.',
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha1->copy()->addMinutes(5),
                'tabla_afectada' => 'ventas',
                'nro_registro'   => (string)$venta1,
                'transaccion'    => 'Venta #' . $venta1 . ' registrada. Tipo: contado. Monto: ' . $monto1 . ' BOB. Método: efectivo.',
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha1->copy()->addMinutes(6),
                'tabla_afectada' => 'inhumaciones',
                'nro_registro'   => '1',
                'transaccion'    => 'Inhumación registrada: Pedro Flores Vaca. Causa: Paro cardíaco. Espacio Nicho A-1.',
            ]);
        }

        // ── CONTRATO 2: Crédito mensual 6 cuotas, 3 pagadas ──────────────────
        // Fechas de cuotas: feb, mar, abr, may, jun, jul 2026
        // Pagadas: feb (01/02), mar (01/03), abr (01/04) → todas <= 19/05/2026 ✓
        // Pendientes: may (01/05), jun (01/06), jul (01/07)
        $esp2             = $espaciosCreados[4]; // Bóveda B-1 Cementerio Municipal
        $monto2           = $calcularMonto($esp2);
        $fecha2           = Carbon::create(2026, 2, 1);
        $interes2         = 12.00;
        $montoConInteres2 = round($monto2 * (1 + $interes2 / 100), 2);
        $montoCuota2      = round($montoConInteres2 / 6, 2);
        // Ajuste de saldo: suma exacta de cuotas pendientes (4, 5, 6)
        $saldo2           = round($montoCuota2 * 3, 2);

        if (!DB::table('contratos')->where('espacio_id', $esp2->id)->exists()) {
            $contrato2 = DB::table('contratos')->insertGetId([
                'cliente_id'      => $clientes[1]->id,
                'espacio_id'      => $esp2->id,
                'fecha_contrato'  => $fecha2->format('Y-m-d'),
                'monto_base'      => $monto2,
                'saldo_pendiente' => $saldo2,
                'estado'          => 'activo',
                'moneda'          => 'BOB',
                'observacion'     => 'Pago en 6 cuotas mensuales con interés del 12% anual.',
                'created_at'      => $fecha2,
                'updated_at'      => $fecha2,
            ]);

            $esp2->update(['estado' => 'ocupado']);

            $venta2 = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato2,
                'cliente_id'   => $clientes[1]->id,
                'empleado_id'  => $empCajero->id,
                'fecha_venta'  => $fecha2->format('Y-m-d'),
                'precio_total' => $montoConInteres2,
                'tipo_venta'   => 'credito',
                'moneda'       => 'BOB',
                'created_at'   => $fecha2,
                'updated_at'   => $fecha2,
            ]);

            $pagoCredito2 = DB::table('pago_creditos')->insertGetId([
                'venta_id'      => $venta2,
                'interes'       => $interes2,
                'monto_inicial' => $montoConInteres2,
                'created_at'    => $fecha2,
                'updated_at'    => $fecha2,
            ]);

            $planPago2 = DB::table('plan_pagos')->insertGetId([
                'pago_credito_id' => $pagoCredito2,
                'fecha_inicio'    => $fecha2->format('Y-m-d'),
                'fecha_fin'       => Carbon::create(2026, 8, 1)->format('Y-m-d'),
                'frecuencia'      => 'mensual',
                'monto'           => $montoCuota2,
                'interes_anual'   => $interes2,
                'created_at'      => $fecha2,
                'updated_at'      => $fecha2,
            ]);

            // Saldo restante para calcular interés real por cuota
            $saldoCapital2 = $monto2;
            $tasaMensual2  = $interes2 / 100 / 12;

            for ($i = 1; $i <= 6; $i++) {
                // Cuotas vencen el 1 de cada mes a partir de febrero 2026
                $fechaVenc2   = Carbon::create(2026, 1 + $i, 1);
                $esPagada     = $i <= 3; // feb, mar, abr ya pasaron al 19/05/2026
                $estadoCuota2 = $esPagada ? 'pagada' : 'pendiente';
                $interesCuota2 = round($saldoCapital2 * $tasaMensual2, 2);
                $capitalCuota2 = round($montoCuota2 - $interesCuota2, 2);
                $saldoCapital2 = max(0, round($saldoCapital2 - $capitalCuota2, 2));

                $cuotaId2 = DB::table('cuotas')->insertGetId([
                    'plan_pago_id'      => $planPago2,
                    'nro_cuota'         => $i,
                    'estado'            => $estadoCuota2,
                    'fecha_vencimiento' => $fechaVenc2->format('Y-m-d'),
                    'monto'             => $montoCuota2,
                    'created_at'        => $fecha2,
                    'updated_at'        => $fecha2,
                ]);

                if ($esPagada) {
                    $metodoPago2 = match ($i) {
                        1 => 'transferencia',
                        2 => 'qr',
                        default => 'efectivo',
                    };

                    DB::table('pagos')->insert([
                        'cuota_id'      => $cuotaId2,
                        'empleado_id'   => $empCajero->id,
                        'fecha_pago'    => $fechaVenc2->format('Y-m-d'),
                        'monto_pagado'  => $montoCuota2,
                        'monto_interes' => $interesCuota2,
                        'metodo_pago'   => $metodoPago2,
                        'comprobante'   => 'COMP-' . strtoupper(substr(md5($cuotaId2), 0, 8)),
                        'created_at'    => $fechaVenc2,
                        'updated_at'    => $fechaVenc2,
                    ]);

                    Bitacora::create([
                        'empleado_id'    => $empCajero->id,
                        'fecha_hora'     => $fechaVenc2,
                        'tabla_afectada' => 'pagos',
                        'nro_registro'   => (string)$cuotaId2,
                        'transaccion'    => 'Pago cuota #' . $i . '/6 contrato #' . $contrato2 . '. Cliente: Ana Gutierrez. Monto: ' . $montoCuota2 . ' BOB. Interés: ' . $interesCuota2 . ' BOB.',
                    ]);
                }
            }

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp2->id,
                'contrato_id'      => $contrato2,
                'nombre'           => 'Lucía',
                'paterno'          => 'Gutierrez',
                'materno'          => 'Torrez',
                'fecha_nacimiento' => '1938-07-20',
                'fecha_defuncion'  => $fecha2->copy()->subDays(2)->format('Y-m-d'),
                'fecha_inhumacion' => $fecha2->format('Y-m-d'),
                'causa_muerte'     => 'Insuficiencia respiratoria',
                'created_at'       => $fecha2,
                'updated_at'       => $fecha2,
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha2,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato2,
                'transaccion'    => 'Registro contrato #' . $contrato2 . ' para Ana Gutierrez. Bóveda B-1. Crédito 6 cuotas al 12% anual. Total: ' . $montoConInteres2 . ' BOB.',
            ]);
        }

        // ── CONTRATO 3: Contado con descuento, pagado ─────────────────────────
        $esp3       = $espaciosCreados[6]; // Terreno C-1 Cementerio Municipal
        $monto3     = $calcularMonto($esp3);
        $descuento3 = 200.00;
        $fecha3     = Carbon::create(2026, 3, 1);

        if (!DB::table('contratos')->where('espacio_id', $esp3->id)->exists()) {
            $contrato3 = DB::table('contratos')->insertGetId([
                'cliente_id'      => $clientes[2]->id,
                'espacio_id'      => $esp3->id,
                'fecha_contrato'  => $fecha3->format('Y-m-d'),
                'monto_base'      => $monto3,
                'saldo_pendiente' => 0,
                'estado'          => 'pagado',
                'moneda'          => 'BOB',
                'observacion'     => 'Se aplicó descuento especial de 200 BOB por cliente frecuente.',
                'created_at'      => $fecha3,
                'updated_at'      => $fecha3,
            ]);

            $esp3->update(['estado' => 'ocupado']);

            $venta3 = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato3,
                'cliente_id'   => $clientes[2]->id,
                'empleado_id'  => $empAdmin->id,
                'fecha_venta'  => $fecha3->format('Y-m-d'),
                'precio_total' => $monto3 - $descuento3,
                'tipo_venta'   => 'contado',
                'moneda'       => 'BOB',
                'created_at'   => $fecha3,
                'updated_at'   => $fecha3,
            ]);

            DB::table('pago_contados')->insertGetId([
                'venta_id'    => $venta3,
                'descuento'   => $descuento3,
                'metodo_pago' => 'transferencia',
                'created_at'  => $fecha3,
                'updated_at'  => $fecha3,
            ]);

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp3->id,
                'contrato_id'      => $contrato3,
                'nombre'           => 'Ernesto',
                'paterno'          => 'Vargas',
                'materno'          => 'Cortez',
                'fecha_nacimiento' => '1950-11-05',
                'fecha_defuncion'  => $fecha3->copy()->subDays(1)->format('Y-m-d'),
                'fecha_inhumacion' => $fecha3->format('Y-m-d'),
                'causa_muerte'     => 'Diabetes mellitus complicada',
                'created_at'       => $fecha3,
                'updated_at'       => $fecha3,
            ]);

            Bitacora::create([
                'empleado_id'    => $empAdmin->id,
                'fecha_hora'     => $fecha3,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato3,
                'transaccion'    => 'Contrato #' . $contrato3 . ' registrado. Cliente: Luis Vargas. Descuento: 200 BOB. Pago por transferencia. Total: ' . ($monto3 - $descuento3) . ' BOB.',
            ]);

            Bitacora::create([
                'empleado_id'    => $empAdmin->id,
                'fecha_hora'     => $fecha3->copy()->addMinutes(10),
                'tabla_afectada' => 'ventas',
                'nro_registro'   => (string)$venta3,
                'transaccion'    => 'Venta #' . $venta3 . ' procesada. Precio final con descuento: ' . ($monto3 - $descuento3) . ' BOB.',
            ]);
        }

        // ── CONTRATO 4: Crédito quincenal 4 cuotas, 1 pagada ─────────────────
        // Cuotas: 15/04, 30/04, 15/05, 30/05 → la del 15/04 ya pasó al 19/05/2026 ✓
        $esp4             = $espaciosCreados[8]; // Nicho A-1 Jardines del Recuerdo
        $monto4           = $calcularMonto($esp4);
        $fecha4           = Carbon::create(2026, 4, 1);
        $interes4         = 15.00;
        $montoConInteres4 = round($monto4 * (1 + $interes4 / 100), 2);
        $montoCuota4      = round($montoConInteres4 / 4, 2);
        $saldo4           = round($montoCuota4 * 3, 2);

        if (!DB::table('contratos')->where('espacio_id', $esp4->id)->exists()) {
            $contrato4 = DB::table('contratos')->insertGetId([
                'cliente_id'      => $clientes[3]->id,
                'espacio_id'      => $esp4->id,
                'fecha_contrato'  => $fecha4->format('Y-m-d'),
                'monto_base'      => $monto4,
                'saldo_pendiente' => $saldo4,
                'estado'          => 'activo',
                'moneda'          => 'BOB',
                'observacion'     => 'Pago quincenal en 4 cuotas. Interés 15% anual.',
                'created_at'      => $fecha4,
                'updated_at'      => $fecha4,
            ]);

            $esp4->update(['estado' => 'ocupado']);

            $venta4 = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato4,
                'cliente_id'   => $clientes[3]->id,
                'empleado_id'  => $empCajero->id,
                'fecha_venta'  => $fecha4->format('Y-m-d'),
                'precio_total' => $montoConInteres4,
                'tipo_venta'   => 'credito',
                'moneda'       => 'BOB',
                'created_at'   => $fecha4,
                'updated_at'   => $fecha4,
            ]);

            $pagoCredito4 = DB::table('pago_creditos')->insertGetId([
                'venta_id'      => $venta4,
                'interes'       => $interes4,
                'monto_inicial' => $montoConInteres4,
                'created_at'    => $fecha4,
                'updated_at'    => $fecha4,
            ]);

            $planPago4 = DB::table('plan_pagos')->insertGetId([
                'pago_credito_id' => $pagoCredito4,
                'fecha_inicio'    => $fecha4->format('Y-m-d'),
                'fecha_fin'       => Carbon::create(2026, 5, 30)->format('Y-m-d'),
                'frecuencia'      => 'quincenal',
                'monto'           => $montoCuota4,
                'interes_anual'   => $interes4,
                'created_at'      => $fecha4,
                'updated_at'      => $fecha4,
            ]);

            $saldoCapital4  = $monto4;
            $tasaQuincenal4 = $interes4 / 100 / 24; // 24 quincenas en el año

            // Fechas fijas de vencimiento quincenal
            $fechasCuotas4 = [
                Carbon::create(2026, 4, 15),
                Carbon::create(2026, 4, 30),
                Carbon::create(2026, 5, 15),
                Carbon::create(2026, 5, 30),
            ];

            foreach ($fechasCuotas4 as $i => $fechaVenc4) {
                $nroCuota4     = $i + 1;
                $esPagada4     = $nroCuota4 === 1; // solo 15/04 está pagada al 19/05/2026
                $estadoCuota4  = $esPagada4 ? 'pagada' : 'pendiente';
                $interesCuota4 = round($saldoCapital4 * $tasaQuincenal4, 2);
                $capitalCuota4 = round($montoCuota4 - $interesCuota4, 2);
                $saldoCapital4 = max(0, round($saldoCapital4 - $capitalCuota4, 2));

                $cuotaId4 = DB::table('cuotas')->insertGetId([
                    'plan_pago_id'      => $planPago4,
                    'nro_cuota'         => $nroCuota4,
                    'estado'            => $estadoCuota4,
                    'fecha_vencimiento' => $fechaVenc4->format('Y-m-d'),
                    'monto'             => $montoCuota4,
                    'created_at'        => $fecha4,
                    'updated_at'        => $fecha4,
                ]);

                if ($esPagada4) {
                    DB::table('pagos')->insert([
                        'cuota_id'      => $cuotaId4,
                        'empleado_id'   => $empCajero->id,
                        'fecha_pago'    => $fechaVenc4->format('Y-m-d'),
                        'monto_pagado'  => $montoCuota4,
                        'monto_interes' => $interesCuota4,
                        'metodo_pago'   => 'qr',
                        'comprobante'   => 'COMP-' . strtoupper(substr(md5($cuotaId4), 0, 8)),
                        'created_at'    => $fechaVenc4,
                        'updated_at'    => $fechaVenc4,
                    ]);

                    Bitacora::create([
                        'empleado_id'    => $empCajero->id,
                        'fecha_hora'     => $fechaVenc4,
                        'tabla_afectada' => 'pagos',
                        'nro_registro'   => (string)$cuotaId4,
                        'transaccion'    => 'Pago cuota #1/4 contrato #' . $contrato4 . '. Cliente: Carmen Mendoza. Monto: ' . $montoCuota4 . ' BOB. Método: QR.',
                    ]);
                }
            }

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp4->id,
                'contrato_id'      => $contrato4,
                'nombre'           => 'Héctor',
                'paterno'          => 'Mendoza',
                'materno'          => 'Suárez',
                'fecha_nacimiento' => '1942-05-18',
                'fecha_defuncion'  => $fecha4->copy()->subDays(2)->format('Y-m-d'),
                'fecha_inhumacion' => $fecha4->format('Y-m-d'),
                'causa_muerte'     => 'Accidente cerebrovascular',
                'created_at'       => $fecha4,
                'updated_at'       => $fecha4,
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha4,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato4,
                'transaccion'    => 'Contrato #' . $contrato4 . ' para Carmen Mendoza. Nicho Jardines A-1. Crédito quincenal 4 cuotas al 15% anual. Total: ' . $montoConInteres4 . ' BOB.',
            ]);
        }

        // ── CONTRATO 5: Contado reciente, pagado con QR ───────────────────────
        $esp5   = $espaciosCreados[1]; // Nicho A-2 Cementerio Municipal
        $monto5 = $calcularMonto($esp5);
        $fecha5 = Carbon::create(2026, 5, 5);

        if (!DB::table('contratos')->where('espacio_id', $esp5->id)->exists()) {
            $contrato5 = DB::table('contratos')->insertGetId([
                'cliente_id'      => $clientes[4]->id,
                'espacio_id'      => $esp5->id,
                'fecha_contrato'  => $fecha5->format('Y-m-d'),
                'monto_base'      => $monto5,
                'saldo_pendiente' => 0,
                'estado'          => 'pagado',
                'moneda'          => 'BOB',
                'observacion'     => null,
                'created_at'      => $fecha5,
                'updated_at'      => $fecha5,
            ]);

            $esp5->update(['estado' => 'ocupado']);

            $venta5 = DB::table('ventas')->insertGetId([
                'contrato_id'  => $contrato5,
                'cliente_id'   => $clientes[4]->id,
                'empleado_id'  => $empCajero->id,
                'fecha_venta'  => $fecha5->format('Y-m-d'),
                'precio_total' => $monto5,
                'tipo_venta'   => 'contado',
                'moneda'       => 'BOB',
                'created_at'   => $fecha5,
                'updated_at'   => $fecha5,
            ]);

            DB::table('pago_contados')->insert([
                'venta_id'    => $venta5,
                'descuento'   => 0,
                'metodo_pago' => 'qr',
                'created_at'  => $fecha5,
                'updated_at'  => $fecha5,
            ]);

            DB::table('inhumaciones')->insert([
                'espacio_id'       => $esp5->id,
                'contrato_id'      => $contrato5,
                'nombre'           => 'Elena',
                'paterno'          => 'Chávez',
                'materno'          => 'Rojas',
                'fecha_nacimiento' => '1955-02-14',
                'fecha_defuncion'  => $fecha5->copy()->subDays(1)->format('Y-m-d'),
                'fecha_inhumacion' => $fecha5->format('Y-m-d'),
                'causa_muerte'     => 'Cáncer de pulmón',
                'created_at'       => $fecha5,
                'updated_at'       => $fecha5,
            ]);

            Bitacora::create([
                'empleado_id'    => $empCajero->id,
                'fecha_hora'     => $fecha5,
                'tabla_afectada' => 'contratos',
                'nro_registro'   => (string)$contrato5,
                'transaccion'    => 'Contrato #' . $contrato5 . ' registrado. Cliente: Jorge Chávez. Nicho A-2. Pago contado por QR. Monto: ' . $monto5 . ' BOB.',
            ]);
        }

        // ────────────────────────────────────────────────
        // 11. MANTENIMIENTOS
        // ────────────────────────────────────────────────
        $mantenimientosData = [
            // [espacio_id, tipo_id, precio, estado, descripcion, empleado_id, fecha_inicio]
            [$espaciosCreados[0]->id, $tipoLimpieza->id,    60.00,  'completado', 'Limpieza mensual del nicho A-1',             $empOperario->id, Carbon::create(2026, 2, 15)],
            [$espaciosCreados[1]->id, $tipoReparacion->id, 220.00,  'completado', 'Reparación de grieta lateral nicho A-2',     $empOperario->id, Carbon::create(2026, 3, 10)],
            [$espaciosCreados[4]->id, $tipoPintura->id,    160.00,  'completado', 'Repintado completo bóveda B-1',              $empOperario->id, Carbon::create(2026, 3, 25)],
            [$espaciosCreados[5]->id, $tipoJardineria->id,  85.00,  'en_proceso', 'Poda y limpieza área verde bóveda B-2',      $empOperario->id, Carbon::create(2026, 5, 10)],
            [$espaciosCreados[6]->id, $tipoLimpieza->id,    70.00,  'pendiente',  'Limpieza programada terreno C-1',            $empOperario->id, Carbon::create(2026, 5, 19)],
            [$espaciosCreados[2]->id, $tipoRenovacion->id, 520.00,  'pendiente',  'Renovación completa nicho A-3',              $empOperario->id, Carbon::create(2026, 5, 19)],
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
            [$empAdmin->id,    Carbon::create(2025, 11, 1),  'cementerios',       '1', 'Registro del Cementerio Municipal San Juan. Capacidad: 500 espacios.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 1),  'cementerios',       '2', 'Registro del cementerio Jardines del Recuerdo. Capacidad: 300 espacios.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 2),  'tipo_inhumaciones', '1', 'Tipo de espacio Nicho creado. Precio/m²: 1400 BOB. Precio inhumación: 500 BOB.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 2),  'tipo_inhumaciones', '2', 'Tipo de espacio Bóveda creado. Precio/m²: 888 BOB. Precio inhumación: 800 BOB.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 2),  'tipo_inhumaciones', '3', 'Tipo de espacio Terreno creado. Precio/m²: 833 BOB. Precio inhumación: 600 BOB.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 5),  'espacios',          '1', 'Espacio Nicho A-1 registrado en Cementerio Municipal San Juan.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 5),  'espacios',          '2', 'Espacio Nicho A-2 registrado en Cementerio Municipal San Juan.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 5),  'espacios',          '3', 'Espacio Bóveda B-1 registrado en Cementerio Municipal San Juan.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 5),  'espacios',          '4', 'Espacio Terreno C-1 registrado en Cementerio Municipal San Juan.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 10), 'empleados',         '2', 'Empleado María López dado de alta como Cajera.'],
            [$empAdmin->id,    Carbon::create(2025, 11, 10), 'empleados',         '3', 'Empleado Carlos Ríos dado de alta como Operario.'],
            [$empCajero->id,   Carbon::create(2025, 12, 1),  'clientes',          '1', 'Cliente Roberto Flores registrado. CI: 4567890.'],
            [$empCajero->id,   Carbon::create(2026, 1, 15),  'clientes',          '2', 'Cliente Ana Gutierrez registrada. CI: 5678901.'],
            [$empCajero->id,   Carbon::create(2026, 2, 20),  'clientes',          '3', 'Cliente Luis Vargas registrado. CI: 6789012.'],
            [$empCajero->id,   Carbon::create(2026, 3, 10),  'clientes',          '4', 'Cliente Carmen Mendoza registrada. CI: 7890123.'],
            [$empCajero->id,   Carbon::create(2026, 4, 1),   'clientes',          '5', 'Cliente Jorge Chávez registrado. CI: 8901234.'],
            [$empOperario->id, Carbon::create(2026, 4, 20),  'espacios',          '3', 'Estado del espacio Nicho A-3 actualizado a: en mantenimiento.'],
            [$empOperario->id, Carbon::create(2026, 5, 2),   'espacios',          '3', 'Estado del espacio Nicho A-3 revisado. Disponible tras inspección.'],
            [$empAdmin->id,    Carbon::create(2026, 5, 16),  'usuarios',          '3', 'Contraseña del usuario cajero restablecida por administrador.'],
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
        $this->command->info('👤 Usuarios creados:');
        $this->command->info('   bruno    / Bruno1234!    → Administrador');
        $this->command->info('   william  / William1234!  → Administrador');
        $this->command->info('   cajero   / cajero1234!   → Cajero');
        $this->command->info('   dennis   / Dennis1234!   → Operario');
        $this->command->info('');
        $this->command->info('📋 Contratos: 5');
        $this->command->info('   #1 Roberto Flores   — Nicho A-1      — Contado    — 01/12/2025 — Pagado');
        $this->command->info('   #2 Ana Gutierrez    — Bóveda B-1     — Crédito    — 01/02/2026 — Activo (3/6 cuotas)');
        $this->command->info('   #3 Luis Vargas      — Terreno C-1    — Contado    — 01/03/2026 — Pagado (desc. 200 BOB)');
        $this->command->info('   #4 Carmen Mendoza   — Nicho Jard. A-1— Crédito    — 01/04/2026 — Activo (1/4 cuotas)');
        $this->command->info('   #5 Jorge Chávez     — Nicho A-2      — Contado QR — 05/05/2026 — Pagado');
        $this->command->info('');
        $this->command->info('🪦 Inhumaciones: 5');
        $this->command->info('🔧 Mantenimientos: 6  (3 completados | 1 en proceso | 2 pendientes)');
        $this->command->info('📝 Registros en bitácora: ~35');
        $this->command->info('');
    }
}