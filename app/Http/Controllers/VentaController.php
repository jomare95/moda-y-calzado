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
    public function index()
    {
        $ventas = Venta::with('cliente')->latest()->paginate(10);
        return view('ventas.index', compact('ventas'));
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
            $request->validate([
                'productos' => 'required|json',
                'tipo_comprobante' => 'required|string',
                'tipo_pago' => 'required|string',
                'subtotal' => 'required|numeric',
                'iva' => 'required|numeric',
                'total' => 'required|numeric'
            ]);

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

            $productos = json_decode($request->productos, true);
            
            foreach ($productos as $producto) {
                // Crear detalle de venta
                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'subtotal' => $producto['subtotal'],
                    'color' => $producto['color'],
                    'talle' => $producto['talle']
                ]);

                // Verificar y actualizar stock
                $productoTalle = DB::table('producto_talles')
                    ->where([
                        'id_producto' => $producto['producto_id'],
                        'talla' => $producto['talle']
                    ])
                    ->lockForUpdate()
                    ->first();

                if (!$productoTalle) {
                    throw new \Exception('No se encontró el talle ' . $producto['talle'] . 
                                       ' para el producto ' . $producto['nombre']);
                }

                if ($productoTalle->stock < $producto['cantidad']) {
                    throw new \Exception('Stock insuficiente para el producto ' . 
                                       $producto['nombre'] . ' talle ' . $producto['talle'] .
                                       '. Stock disponible: ' . $productoTalle->stock);
                }

                // Actualizar el stock
                $affected = DB::table('producto_talles')
                    ->where([
                        'id_producto' => $producto['producto_id'],
                        'talla' => $producto['talle']
                    ])
                    ->decrement('stock', $producto['cantidad']);

                if ($affected == 0) {
                    throw new \Exception('No se pudo actualizar el stock del producto ' . 
                                       $producto['nombre'] . ' talle ' . $producto['talle']);
                }
            }

            DB::commit();

            // Redirigir a la página de confirmación
            return redirect()->route('ventas.boleta', $venta->id_venta);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en store:', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
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
} 