<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    InventarioController,
    VentaController,
    PagoController,
    ResumenController
};

// Inicio → resumen del día
Route::get('/', fn() => redirect()->route('resumen.index'))->name('home');

// Inventario
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::patch('/inventario/{producto}', [InventarioController::class, 'actualizar'])->name('inventario.actualizar');

// Ventas
Route::get('/ventas/nueva', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

// Pagos a crédito
Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
Route::post('/pagos/{venta}', [PagoController::class, 'store'])->name('pagos.store');

// Resumen diario
Route::get('/resumen', [ResumenController::class, 'index'])->name('resumen.index');