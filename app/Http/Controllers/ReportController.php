<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function reportes(Request $request)
    {
        // Verificar si el usuario autenticado es un administrador
        $user = Auth::user();
        if ($user->rol !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta sección.');
        }

        try {
            // Obtener todas las ventas, incluyendo el estado
            $ventas = Venta::with(['cliente'])->orderBy('fecha_venta', 'desc')->get();

            // Obtener todas las compras, incluyendo el proveedor
            $compras = Compra::with('proveedor')->orderBy('fecha_compra', 'desc')->get();

            // Calcular totales y balance
            $totalVentas = $ventas->sum('total');
            $totalCompras = $compras->sum('total');
            $balance = $totalVentas - $totalCompras;

            return view('reportes.index', compact('ventas', 'compras', 'totalVentas', 'totalCompras', 'balance'));
        } catch (\Exception $e) {
            \Log::error('Error al cargar reportes: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar los reportes');
        }
    }
}