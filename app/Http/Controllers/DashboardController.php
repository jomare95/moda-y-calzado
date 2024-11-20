<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas básicas
            $ventasHoy = Venta::whereDate('fecha_venta', Carbon::today())
                ->where('estado', 'Completada')
                ->sum('total');

            $ingresosHoy = $ventasHoy; // Si tienes otros ingresos, súmalos aquí

            $gastosHoy = 0; // Implementar lógica de gastos si la tienes

            $balanceHoy = $ingresosHoy - $gastosHoy;

            // Productos más vendidos hoy
            $productosMasVendidosHoy = DB::table('productos')
                ->join('detalle_ventas', 'productos.id_producto', '=', 'detalle_ventas.id_producto')
                ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
                ->select(
                    'productos.nombre',
                    DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                    DB::raw('SUM(detalle_ventas.subtotal) as total_vendido')
                )
                ->whereDate('ventas.fecha_venta', Carbon::today())
                ->where('ventas.estado', 'Completada')
                ->groupBy('productos.id_producto', 'productos.nombre')
                ->orderBy('cantidad_vendida', 'desc')
                ->limit(5)
                ->get();

            // Últimas ventas
            $ultimasVentas = Venta::with('cliente')
                ->where('estado', 'Completada')
                ->latest('fecha_venta')
                ->limit(5)
                ->get();

            // Últimas compras (si tienes un módulo de compras)
            $ultimasCompras = collect([]); // Implementar si tienes módulo de compras

            // Datos para el gráfico de ventas (últimos 7 días)
            $ventasUltimos7Dias = Venta::select(
                DB::raw('DATE(fecha_venta) as fecha'),
                DB::raw('SUM(total) as total')
            )
                ->where('estado', 'Completada')
                ->whereBetween('fecha_venta', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay()
                ])
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            $ventasChart = [
                'labels' => $ventasUltimos7Dias->pluck('fecha')->map(function($fecha) {
                    return Carbon::parse($fecha)->format('d/m');
                })->toArray(),
                'data' => $ventasUltimos7Dias->pluck('total')->toArray()
            ];

            return view('dashboard', compact(
                'ventasHoy',
                'ingresosHoy',
                'gastosHoy',
                'balanceHoy',
                'productosMasVendidosHoy',
                'ultimasVentas',
                'ultimasCompras',
                'ventasChart'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en Dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }
}
