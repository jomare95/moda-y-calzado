<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Productos
    Route::resource('productos', ProductoController::class);
    Route::resource('categorias', CategoriaController::class);
    
    // Compras
    Route::resource('compras', CompraController::class);
    Route::get('compras/print/{compra}', [CompraController::class, 'print'])->name('compras.print');
    Route::resource('proveedores', ProveedorController::class);
    
    // Ventas
    Route::resource('ventas', VentaController::class);
    Route::resource('clientes', ClienteController::class);
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Agregar esta ruta junto con las otras rutas de ventas
    Route::get('ventas/{venta}/print', [VentaController::class, 'print'])->name('ventas.print');
    
    Route::patch('/productos/{producto}/toggle-status', [ProductoController::class, 'toggleStatus'])
        ->name('productos.toggle-status');
    
    Route::get('/ventas/boleta/{id}', [VentaController::class, 'mostrarBoleta'])->name('ventas.boleta');
    
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    
    Route::get('ventas/comprobante/{id}', [VentaController::class, 'mostrarComprobante'])->name('ventas.comprobante');
    
    Route::post('ventas/{id}/anular', [VentaController::class, 'anular'])->name('ventas.anular');
    
    Route::get('/dashboard/exportar-productos-vendidos', [DashboardController::class, 'exportarProductosVendidosHoy'])
        ->name('dashboard.exportar-productos-vendidos');
    
    // Ruta para la vista de reportes
    Route::get('/reportes', [ReportController::class, 'reportes'])->name('reportes.index');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::get('/api/productos/{id}/detalles', [VentaController::class, 'getProductoDetalles'])->name('productos.detalles');

Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');

Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');

// Route::get('/productos/{id}/detalles', [VentaController::class, 'getProductoDetalles']);

Route::post('/marcas', [MarcaController::class, 'store']);

Route::get('/ventas/{venta}/comprobante', [VentaController::class, 'mostrarComprobante'])->name('ventas.comprobante');

Route::get('/categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
