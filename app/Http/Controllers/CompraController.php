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
        try {
            DB::beginTransaction();

            // Crear la compra
            $compra = new Compra();
            $compra->id_proveedor = $request->id_proveedor;
            $compra->numero_comprobante = $request->numero_comprobante;
            $compra->fecha_compra = $request->fecha_compra;
            $compra->tipo_comprobante = $request->tipo_comprobante;
            $compra->subtotal = $request->subtotal;
            $compra->iva = $request->iva;
            $compra->total = $request->total;
            $compra->estado = 'Completada';
            $compra->save();

            // Guardar detalles
            foreach($request->productos as $key => $id_producto) {
                $detalle = new DetalleCompra();
                $detalle->id_compra = $compra->id_compra;
                $detalle->id_producto = $id_producto;
                $detalle->cantidad = $request->cantidades[$key];
                $detalle->precio_unitario = $request->precios[$key];
                $detalle->subtotal = $request->subtotales[$key];
                $detalle->save();

                // Actualizar stock del producto
                $producto = Producto::find($id_producto);
                $producto->stock += $request->cantidades[$key];
                $producto->save();
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function print(Compra $compra)
    {
        return view('compras.print', [
            'compra' => $compra->load('proveedor', 'detalles.producto')
        ]);
    }
} 