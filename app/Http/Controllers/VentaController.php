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
        $clientes = Cliente::where('estado', 1)->get();
        $productos = Producto::with(['colores', 'talles'])->where('estado', 1)->get();
        
        return view('ventas.create', compact('clientes', 'productos'));
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
                    ->lockForUpdate()  // Bloquear el registro para evitar condiciones de carrera
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

                // Actualizar el stock usando la clave primaria compuesta
                $affected = DB::table('producto_talles')
                    ->where([
                        'id_producto' => $producto['producto_id'],
                        'talla' => $producto['talle']
                    ])
                    ->update([
                        'stock' => DB::raw('stock - ' . $producto['cantidad'])
                    ]);

                if (!$affected) {
                    throw new \Exception('No se pudo actualizar el stock del producto ' . 
                                       $producto['nombre'] . ' talle ' . $producto['talle']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Venta registrada correctamente',
                'numero_comprobante' => $numeroComprobante,
                'id_venta' => $venta->id_venta
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en venta:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error al registrar la venta: ' . $e->getMessage()
            ]);
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
            $producto = Producto::findOrFail($id);
            
            \Log::info('Buscando detalles para producto:', ['id' => $id]);
            
            $colores = ProductoColor::where('id_producto', $id)->get();
            $talles = ProductoTalle::where('id_producto', $id)->get();
            
            \Log::info('Datos encontrados:', [
                'colores' => $colores->toArray(),
                'talles' => $talles->toArray(),
                'precio' => $producto->precio_venta
            ]);

            return response()->json([
                'colores' => $colores,
                'talles' => $talles,
                'precio' => $producto->precio_venta
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getProductoDetalles: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function mostrarBoleta($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.boleta', compact('venta'));
    }
} 