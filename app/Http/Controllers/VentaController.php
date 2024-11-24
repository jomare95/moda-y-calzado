<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\ProductoColor;
use App\Models\ProductoTalle;
use Illuminate\Http\Request;
use DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Venta::with(['cliente', 'usuario']);

            // Filtro por fecha inicio
            if ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha_venta', '>=', $request->fecha_inicio);
            }

            // Filtro por fecha fin
            if ($request->filled('fecha_fin')) {
                $query->whereDate('fecha_venta', '<=', $request->fecha_fin);
            }

            $ventas = $query->orderBy('fecha_venta', 'desc')
                           ->paginate(10)
                           ->withQueryString();

            return view('ventas.index', compact('ventas'));
        } catch (\Exception $e) {
            \Log::error('Error en VentaController@index: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar las ventas');
        }
    }

    public function create()
    {
        $productos = Producto::where('estado', 1)
                            ->orderBy('nombre')
                            ->get();
        
        $clientes = Cliente::where('estado', 1)
                          ->orderBy('nombre')
                          ->get();
        
        return view('ventas.create', compact('productos', 'clientes'));
    }

    public function store(Request $request)
    {
        try {
            // Decodificar el JSON de productos
            $productos = json_decode($request->productos, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error al decodificar los productos');
            }

            DB::beginTransaction();

            // Generar número de comprobante
            $ultimaVenta = Venta::latest()->first();
            $ultimoNumero = $ultimaVenta ? intval(substr($ultimaVenta->numero_comprobante, -8)) : 0;
            $nuevoNumero = str_pad($ultimoNumero + 1, 8, '0', STR_PAD_LEFT);
            
            $numeroComprobante = sprintf(
                '%s-%s-%s',
                substr($request->tipo_comprobante, 0, 1),
                date('Ymd'),
                $nuevoNumero
            );

            // Crear la venta
            $venta = Venta::create([
                'id_cliente' => $request->id_cliente,
                'id_usuario' => auth()->id(),
                'numero_comprobante' => $numeroComprobante,
                'fecha_venta' => now(),
                'tipo_comprobante' => $request->tipo_comprobante,
                'tipo_pago' => $request->tipo_pago,
                'subtotal' => $request->subtotal,
                'iva' => $request->iva,
                'impuestos' => $request->iva,
                'total' => $request->total,
                'estado' => 'Completada'
            ]);

            // Guardar detalles y actualizar stock
            foreach ($productos as $producto) {
                // Crear detalle de venta
                $detalle = $venta->detalles()->create([
                    'id_producto' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'subtotal' => $producto['subtotal'],
                    'talle' => $producto['talle'],
                    'color' => $producto['color']
                ]);

                // Actualizar stock en producto_talles
                if (!empty($producto['talle'])) {
                    DB::table('producto_talles')
                        ->where('id_producto', $producto['producto_id'])
                        ->where('talla', $producto['talle'])
                        ->decrement('stock', $producto['cantidad']);
                }

                // Recalcular y actualizar stock total del producto
                $stockTotal = DB::table('producto_talles')
                    ->where('id_producto', $producto['producto_id'])
                    ->sum('stock');

                DB::table('productos')
                    ->where('id_producto', $producto['producto_id'])
                    ->update(['stock' => $stockTotal]);
            }

            DB::commit();
            return response()->json(['success' => true, 'id' => $venta->id_venta]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear venta: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function show(Venta $venta)
    {
        $venta->load('detalles.producto', 'cliente');
        return view('ventas.show', compact('venta'));
    }

    public function print(Venta $venta)
    {
        $venta->load('detalles.producto', 'cliente');
        
        return view('ventas.print', compact('venta'));
    }

    public function getProductoDetalles($id)
    {
        try {
            \Log::info('Iniciando getProductoDetalles', ['id' => $id]);
            
            $producto = Producto::findOrFail($id);
            
            // Simplificar la estructura de los datos
            $colores = ProductoColor::where('id_producto', $id)
                ->get()
                ->map(function($color) {
                    return [
                        'color' => $color->color
                    ];
                });
            
            $talles = ProductoTalle::where('id_producto', $id)
                ->get()
                ->map(function($talle) {
                    return [
                        'talla' => $talle->talla,
                        'stock' => $talle->stock
                    ];
                });
            
            $response = [
                'colores' => $colores->values()->toArray(),
                'talles' => $talles->values()->toArray(),
                'precio' => $producto->precio_venta
            ];
            
            \Log::info('Enviando respuesta simplificada', $response);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Error en getProductoDetalles', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function mostrarBoleta($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.boleta', compact('venta'));
    }

    public function mostrarComprobante($id)
    {
        try {
            $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
            
            \Log::info('Mostrando comprobante:', [
                'id_venta' => $id,
                'tipo_comprobante' => $venta->tipo_comprobante
            ]);

            $vista = match($venta->tipo_comprobante) {
                'Boleta' => 'ventas.comprobantes.boleta',
                'Factura' => 'ventas.comprobantes.factura',
                'Ticket' => 'ventas.comprobantes.ticket',
                default => 'ventas.comprobantes.boleta'
            };

            \Log::info('Vista seleccionada:', ['vista' => $vista]);

            return view($vista, compact('venta'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar comprobante:', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al mostrar el comprobante');
        }
    }

    public function anular($id)
    {
        try {
            DB::beginTransaction();

            $venta = Venta::findOrFail($id);
            
            // Verificar que la venta no esté ya anulada
            if ($venta->estado === 'Anulada') {
                return response()->json([
                    'success' => false,
                    'message' => 'La venta ya está anulada'
                ]);
            }

            // Restaurar stock
            foreach ($venta->detalles as $detalle) {
                // Restaurar stock en producto_talles
                if (!empty($detalle->talle)) {
                    DB::table('producto_talles')
                        ->where('id_producto', $detalle->id_producto)
                        ->where('talla', $detalle->talle)
                        ->increment('stock', $detalle->cantidad);
                }

                // Recalcular y actualizar stock total del producto
                $stockTotal = DB::table('producto_talles')
                    ->where('id_producto', $detalle->id_producto)
                    ->sum('stock');

                DB::table('productos')
                    ->where('id_producto', $detalle->id_producto)
                    ->update(['stock' => $stockTotal]);
            }

            // Actualizar estado de la venta
            $venta->update(['estado' => 'Anulada']);

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al anular venta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al anular la venta: ' . $e->getMessage()
            ]);
        }
    }
} 