<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Compra;
use App\Models\Caja;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function index()
    {
        // Obtener fecha actual
        $hoy = Carbon::today();
        
        // Ventas del día
        $ventasHoy = Venta::whereDate('fecha_venta', $hoy)->count();
        
        // Ingresos por ventas del día
        $ingresosHoy = Venta::whereDate('fecha_venta', $hoy)
                            ->where('estado', 'Completada')
                            ->sum('total');
        
        // Gastos por compras del día
        $gastosHoy = Compra::whereDate('fecha_compra', $hoy)
                           ->where('estado', 'Completada')
                           ->sum('total');
        
        // Balance del día (ingresos - gastos)
        $balanceHoy = $ingresosHoy - $gastosHoy;
        
        // Productos más vendidos hoy
        $productosMasVendidosHoy = DetalleVenta::join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->whereDate('ventas.fecha_venta', $hoy)
            ->where('ventas.estado', 'Completada')
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as total_vendido')
            )
            ->groupBy('productos.id_producto', 'productos.nombre')
            ->orderBy('cantidad_vendida', 'desc')
            ->limit(5)
            ->get();
        
        // Datos para el gráfico de ventas últimos 7 días
        $ventasUltimos7Dias = Venta::where('estado', 'Completada')
            ->where('fecha_venta', '>=', Carbon::now()->subDays(6))
            ->select(
                DB::raw('DATE(fecha_venta) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('fecha')
            ->get();
        
        $ventasChart = [
            'labels' => $ventasUltimos7Dias->pluck('fecha')->map(function($fecha) {
                return Carbon::parse($fecha)->format('d/m');
            }),
            'data' => $ventasUltimos7Dias->pluck('total')
        ];
        
        // Últimas ventas
        $ultimasVentas = Venta::with('cliente')
                             ->where('estado', 'Completada')
                             ->latest('fecha_venta')
                             ->limit(5)
                             ->get();
        
        // Últimas compras
        $ultimasCompras = Compra::with(['proveedor' => function($query) {
            $query->withDefault([
                'nombre' => 'Proveedor No Especificado'
            ]);
        }])
        ->where('estado', 'Completada')
        ->latest('fecha_compra')
        ->limit(5)
        ->get();

        // Gráfico de gastos últimos 7 días
        $gastosUltimos7Dias = Compra::where('estado', 'Completada')
            ->where('fecha_compra', '>=', Carbon::now()->subDays(6))
            ->select(
                DB::raw('DATE(fecha_compra) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('fecha')
            ->get();

        $gastosChart = [
            'labels' => $gastosUltimos7Dias->pluck('fecha')->map(function($fecha) {
                return Carbon::parse($fecha)->format('d/m');
            }),
            'data' => $gastosUltimos7Dias->pluck('total')
        ];

        return view('dashboard', compact(
            'ventasHoy',
            'ingresosHoy',
            'gastosHoy',
            'balanceHoy',
            'productosMasVendidosHoy',
            'ventasChart',
            'gastosChart',
            'ultimasVentas',
            'ultimasCompras'
        ));

         // Obtener la última caja abierta
         $cajaAbierta = Caja::where('estado', 'Abierta')->first();

         // Aquí puedes agregar otras consultas necesarias para las variables que usas en la vista
         $ventasHoy = 0; // Reemplaza con la lógica para obtener las ventas del día
         $ingresosHoy = 0; // Reemplaza con la lógica para obtener los ingresos del día
         $gastosHoy = 0; // Reemplaza con la lógica para obtener los gastos del día
         $balanceHoy = $ingresosHoy - $gastosHoy; // Ejemplo de cálculo de balance
         $productosMasVendidosHoy = []; // Reemplaza con la lógica para obtener los productos más vendidos
         $ultimasVentas = []; // Reemplaza con la lógica para obtener las últimas ventas
         $ultimasCompras = []; // Reemplaza con la lógica para obtener las últimas compras
 
         // Asegúrate de que la variable $cajaAbierta se pase a la vista
         return view('dashboard', compact('cajaAbierta', 'ventasHoy', 'ingresosHoy', 'gastosHoy', 'balanceHoy', 'productosMasVendidosHoy', 'ultimasVentas', 'ultimasCompras'));
        
    }
}
