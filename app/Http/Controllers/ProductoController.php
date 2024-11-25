<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Producto::with(['categoria', 'marca']);

            // Búsqueda por nombre o código
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('codigo', 'LIKE', "%{$search}%");
                });
            }

            // Filtro por categoría
            if ($request->filled('categoria')) {
                $query->where('id_categoria', $request->categoria);
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtro por stock
            if ($request->filled('stock')) {
                switch ($request->stock) {
                    case 'bajo':
                        $query->whereRaw('stock <= stock_minimo AND stock > 0');
                        break;
                    case 'sin':
                        $query->where('stock', 0);
                        break;
                    case 'con':
                        $query->where('stock', '>', 0);
                        break;
                }
            }

            $productos = $query->orderBy('nombre')->paginate(10);
            $categorias = Categoria::where('estado', 1)->get();
            
            $totalProductos = Producto::count();
            $productosActivos = Producto::where('estado', 1)->count();
            $productosBajoStock = Producto::whereRaw('stock <= stock_minimo')->count();

            return view('productos.index', compact(
                'productos', 
                'categorias',
                'totalProductos', 
                'productosActivos', 
                'productosBajoStock'
            ));
        } catch (\Exception $e) {
            \Log::error('Error en ProductoController@index: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar los productos');
        }
    }

    public function create()
    {
        $categorias = Categoria::where('estado', 1)->get();
        $marcas = Marca::where('estado', 1)->get();
        $proveedores = Proveedor::where('estado', 1)->get();
        $producto = new Producto();

        return view('productos.create', compact('categorias', 'marcas', 'proveedores', 'producto'));
    }

    public function store(Request $request)
    {
        try {
            // Validación básica
            $request->validate([
                'codigo' => 'required|unique:productos,codigo',
                'nombre' => 'required',
                'id_categoria' => 'required',
                'id_marca' => 'required',
                'id_proveedor' => 'required',
                'tipo_producto' => 'required|in:calzado,ropa',
                'precio_compra' => 'required|numeric',
                'precio_venta' => 'required|numeric',
                'stock_minimo' => 'required|numeric',
            ]);

            DB::beginTransaction();

            // Crear el producto base
            $producto = Producto::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_categoria' => $request->id_categoria,
                'id_marca' => $request->id_marca,
                'id_proveedor' => $request->id_proveedor,
                'tipo_producto' => $request->tipo_producto,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'stock_minimo' => $request->stock_minimo,
                'stock' => 0,
                'estado' => 1
            ]);

            $stockTotal = 0;

            // Para calzado
            if ($request->tipo_producto === 'calzado' && $request->has('talles_calzado')) {
                foreach ($request->talles_calzado as $talle) {
                    $stock = isset($request->stock_talle_calzado[$talle]) ? (int)$request->stock_talle_calzado[$talle] : 0;
                    if ($stock > 0) {
                        $producto->talles()->create([
                            'talla' => $talle,
                            'stock' => $stock
                        ]);
                        $stockTotal += $stock;
                    }
                }
            }

            // Para ropa
            if ($request->tipo_producto === 'ropa') {
                // Guardar talles y stock
                if ($request->has('talles_ropa')) {
                    foreach ($request->talles_ropa as $talle) {
                        $stock = isset($request->stock_talle_ropa[$talle]) ? (int)$request->stock_talle_ropa[$talle] : 0;
                        if ($stock > 0) {
                            $producto->talles()->create([
                                'talla' => $talle,
                                'stock' => $stock
                            ]);
                            $stockTotal += $stock;
                        }
                    }
                }

                // Guardar colores
                if ($request->has('colores_ropa')) {
                    foreach ($request->colores_ropa as $color) {
                        $producto->colores()->create([
                            'color' => $color
                        ]);
                    }
                }
            }

            // Actualizar stock total del producto
            $producto->update(['stock' => $stockTotal]);

            DB::commit();
            return redirect()->route('productos.index')
                ->with('success', '¡Producto guardado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear producto:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function edit(Producto $producto)
    {
        try {
            $categorias = Categoria::where('estado', 1)->orderBy('nombre')->get();
            $marcas = Marca::where('estado', 1)->orderBy('nombre')->get();
            
            return view('productos.edit', compact('producto', 'categorias', 'marcas'));
        } catch (\Exception $e) {
            \Log::error('Error en edición de producto: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el formulario de edición');
        }
    }

    public function update(Request $request, Producto $producto)
    {
        try {
            DB::beginTransaction();

            // Actualizar datos básicos del producto
            $producto->update($request->except(['talles_calzado', 'talles_ropa', 'colores_ropa', 'stock_talle_calzado', 'stock_talle_ropa']));

            // Eliminar talles y colores existentes
            $producto->talles()->delete();
            $producto->colores()->delete();

            $stockTotal = 0;

            // Procesar talles según el tipo de producto
            if ($request->tipo_producto === 'calzado' && $request->has('talles_calzado')) {
                foreach ($request->talles_calzado as $talle) {
                    $stock = isset($request->stock_talle_calzado[$talle]) ? (int)$request->stock_talle_calzado[$talle] : 0;
                    if ($stock > 0) {
                        $producto->talles()->create([
                            'talla' => $talle,
                            'stock' => $stock
                        ]);
                        $stockTotal += $stock;
                    }
                }
            } elseif ($request->tipo_producto === 'ropa' && $request->has('talles_ropa')) {
                foreach ($request->talles_ropa as $talle) {
                    $stock = isset($request->stock_talle_ropa[$talle]) ? (int)$request->stock_talle_ropa[$talle] : 0;
                    if ($stock > 0) {
                        $producto->talles()->create([
                            'talla' => $talle,
                            'stock' => $stock
                        ]);
                        $stockTotal += $stock;
                    }
                }

                // Procesar colores para ropa
                if ($request->has('colores_ropa')) {
                    foreach ($request->colores_ropa as $color) {
                        $producto->colores()->create(['color' => $color]);
                    }
                }
            }

            // Actualizar stock total
            $producto->update(['stock' => $stockTotal]);

            DB::commit();
            return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar producto:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine()
            ]);
            return back()->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function bajoStock()
    {
        $productos = Producto::with(['categoria', 'marca'])
            ->whereRaw('stock <= stock_minimo')
            ->latest()
            ->paginate(10);

        return view('productos.bajo-stock', compact('productos'));
    }

    public function toggleStatus(Producto $producto)
    {
        try {
            $producto->estado = !$producto->estado;
            $producto->save();

            return back()->with('success', 'Estado del producto actualizado correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al cambiar estado del producto: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el estado del producto');
        }
    }

    public function show(Producto $producto)
    {
        try {
            return view('productos.show', compact('producto'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar producto: ' . $e->getMessage());
            return back()->with('error', 'Error al mostrar los detalles del producto');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $producto = Producto::findOrFail($id);
            
            // Eliminar registros relacionados en orden
            DB::table('producto_colores')->where('id_producto', $id)->delete();
            DB::table('producto_talles')->where('id_producto', $id)->delete();
            DB::table('detalle_compras')->where('id_producto', $id)->delete();
            DB::table('detalle_ventas')->where('id_producto', $id)->delete();
            DB::table('movimientos_inventario')->where('id_producto', $id)->delete();
            
            // Finalmente eliminamos el producto
            $producto->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTallesColores(Producto $producto)
    {
        try {
            $talles = DB::table('producto_talles')
                ->where('id_producto', $producto->id_producto)
                ->where('stock', '>', 0)  // Solo talles con stock disponible
                ->select('talla', 'stock')
                ->get();

            // Obtener colores para ambos tipos de productos
            $colores = DB::table('producto_colores')
                ->where('id_producto', $producto->id_producto)
                ->pluck('color');

            return response()->json([
                'success' => true,
                'tipo_producto' => $producto->tipo_producto,
                'talles' => $talles,
                'colores' => $colores,
                'precio_venta' => $producto->precio_venta
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getTallesColores: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 