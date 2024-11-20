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
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::get('/api/productos/{id}/detalles', [VentaController::class, 'getProductoDetalles'])->name('productos.detalles');
