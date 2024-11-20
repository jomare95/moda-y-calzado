<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor')->latest()->paginate(10);
        return view('compras.index', compact('compras'));
    }

    public function create(Request $request)
    {
        $proveedores = Proveedor::where('estado', 1)->get();
        $productos = Producto::where('estado', 1)->get();
        $productoPreseleccionado = null;
        
        if ($request->has('producto_id')) {
            $productoPreseleccionado = Producto::find($request->producto_id);
        }
        
        return view('compras.create', compact('proveedores', 'productos', 'productoPreseleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'numero_comprobante' => 'required|string|max:50',
            'tipo_comprobante' => 'required|in:Factura,Remito,Otro',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Crear la compra
            $compra = new Compra();
            $compra->id_proveedor = $request->id_proveedor;
            $compra->numero_comprobante = $request->numero_comprobante;
            $compra->tipo_comprobante = $request->tipo_comprobante;
            $compra->fecha_compra = now();
            $compra->estado = 'Completada';
            $compra->notas = $request->notas;

            $subtotal = 0;
            
            // Calcular subtotal y guardar detalles
            foreach ($request->productos as $item) {
                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];
                $subtotal += $subtotalItem;
            }

            // Calcular IGV (18%)
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            $compra->subtotal = $subtotal;
            $compra->igv = $igv;
            $compra->total = $total;
            $compra->save();

            // Guardar detalles y actualizar stock
            foreach ($request->productos as $item) {
                $detalle = new DetalleCompra();
                $detalle->id_compra = $compra->id_compra;
                $detalle->id_producto = $item['id_producto'];
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $item['precio_unitario'];
                $detalle->subtotal = $item['cantidad'] * $item['precio_unitario'];
                $detalle->save();

                // Actualizar stock del producto
                $producto = Producto::find($item['id_producto']);
                $producto->stock += $item['cantidad'];
                $producto->save();

                // Registrar movimiento de inventario
                $movimiento = new MovimientoInventario();
                $movimiento->id_producto = $item['id_producto'];
                $movimiento->tipo_movimiento = 'Entrada';
                $movimiento->cantidad = $item['cantidad'];
                $movimiento->motivo = 'Compra #' . $compra->id_compra;
                $movimiento->id_usuario = auth()->id();
                $movimiento->save();
            }

            DB::commit();
            return redirect()->route('compras.index')
                ->with('success', 'Compra registrada correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }
} 