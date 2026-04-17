<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CementerioController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\TipoInhumacionController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\InhumacionController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RolController;

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulos CRUD
    Route::resource('clientes',          ClienteController::class);
    Route::resource('empleados',         EmpleadoController::class);
    Route::resource('usuarios',          UsuarioController::class);
    Route::resource('cementerios',       CementerioController::class);
    Route::resource('espacios',          EspacioController::class);
    Route::resource('tipo_inhumaciones', TipoInhumacionController::class);
    Route::resource('mantenimientos',    MantenimientoController::class);
    Route::resource('inhumaciones',      InhumacionController::class);
    Route::resource('contratos',         ContratoController::class);
    Route::resource('ventas',            VentaController::class);
    Route::resource('roles',             RolController::class)->except(['show']);

    // Rutas especiales ANTES del resource de pagos
    Route::post('/pagos/marcar-vencidas', [PagoController::class, 'marcarVencidas'])
        ->name('pagos.marcarVencidas');
    Route::resource('pagos', PagoController::class);

    // Correos manuales
    Route::post('/ventas/{venta}/enviar-factura', [VentaController::class, 'enviarFactura'])
        ->name('ventas.enviarFactura');
    Route::post('/pagos/{pago}/enviar-recibo', [PagoController::class, 'enviarRecibo'])
        ->name('pagos.enviarRecibo');

    // Bitácora
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');

    // Reportes
    Route::get('/reportes',           [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ventas',    [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('/reportes/pagos',     [ReporteController::class, 'pagos'])->name('reportes.pagos');
    Route::get('/reportes/espacios',  [ReporteController::class, 'espacios'])->name('reportes.espacios');
    Route::get('/reportes/contratos', [ReporteController::class, 'contratos'])->name('reportes.contratos');

    // Perfil
    Route::get('/perfil',          [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil',          [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password', [PerfilController::class, 'cambiarPassword'])->name('perfil.password');
});
