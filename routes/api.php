<?php

use App\Http\Controllers\VentaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/productos/{id}/detalles', [VentaController::class, 'getProductoDetalles']);
Route::get('/productos/{producto}/talles-colores', [ProductoController::class, 'getTallesColores']);