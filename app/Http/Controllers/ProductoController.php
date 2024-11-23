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
    public function index()
    {
        try {
            $productos = Producto::with(['categoria', 'marca'])
                ->orderBy('nombre')
                ->paginate(10);

            $totalProductos = Producto::count();
            $productosActivos = Producto::where('estado', 1)->count();
            $productosBajoStock = Producto::whereRaw('stock <= stock_minimo')->count();

            return view('productos.index', compact(
                'productos', 
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

            // Para ropa
            if ($request->tipo_producto === 'ropa' && $request->has('talles_ropa')) {
                // Guardar talles y stock
                foreach ($request->talles_ropa as $talle) {
                    if (isset($request->stock_talle_ropa[$talle]) && $request->stock_talle_ropa[$talle] > 0) {
                        $stock = (int)$request->stock_talle_ropa[$talle];
                        DB::table('producto_talles')->insert([
                            'id_producto' => $producto->id_producto,
                            'talla' => $talle,
                            'stock' => $stock
                        ]);
                        $stockTotal += $stock;
                    }
                }

                // Guardar colores
                if ($request->has('colores_ropa')) {
                    foreach ($request->colores_ropa as $color) {
                        DB::table('producto_colores')->insert([
                            'id_producto' => $producto->id_producto,
                            'color' => $color
                        ]);
                    }
                }
            }

            // Para calzado
            if ($request->tipo_producto === 'calzado' && $request->has('talles_calzado')) {
                foreach ($request->talles_calzado as $talle) {
                    if (isset($request->stock_talle_calzado[$talle]) && $request->stock_talle_calzado[$talle] > 0) {
                        $stock = (int)$request->stock_talle_calzado[$talle];
                        DB::table('producto_talles')->insert([
                            'id_producto' => $producto->id_producto,
                            'talla' => $talle,
                            'stock' => $stock
                        ]);
                        $stockTotal += $stock;
                    }
                }
            }

            // Actualizar stock total del producto
            $producto->update(['stock' => $stockTotal]);

            DB::commit();
            return redirect()->route('productos.index')
                ->with('success', '¡Producto guardado exitosamente! Se registraron los talles y colores seleccionados.');

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
            $validated = $request->validate([
                'codigo' => 'required|unique:productos,codigo,' . $producto->id_producto . ',id_producto',
                'nombre' => 'required|max:100',
                'id_categoria' => 'required|exists:categorias,id_categoria',
                'id_marca' => 'required|exists:marcas,id_marca',
                'descripcion' => 'nullable|max:255',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'talla' => 'nullable|max:10',
                'color' => 'nullable|max:30',
                'material' => 'nullable|max:50',
                'genero' => 'nullable|in:Hombre,Mujer,Unisex,Niño,Niña',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            DB::beginTransaction();

            // Manejar la imagen si se subió una nueva
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($producto->imagen && file_exists(public_path('images/productos/' . $producto->imagen))) {
                    unlink(public_path('images/productos/' . $producto->imagen));
                }
                
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->move(public_path('images/productos'), $nombreImagen);
                $producto->imagen = $nombreImagen;
            }

            $producto->update([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'id_categoria' => $request->id_categoria,
                'id_marca' => $request->id_marca,
                'descripcion' => $request->descripcion,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock,
                'stock_minimo' => $request->stock_minimo,
                'talla' => $request->talla,
                'color' => $request->color,
                'material' => $request->material,
                'genero' => $request->genero,
                'estado' => 1
            ]);

            DB::commit();
            return redirect()->route('productos.index')
                ->with('success', 'Producto actualizado correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar producto: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
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
            
            // Primero eliminamos los registros relacionados
            DB::table('detalle_ventas')->where('id_producto', $id)->delete();
            DB::table('producto_talles')->where('id_producto', $id)->delete();
            
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
        $talles = DB::table('producto_talles')
            ->where('id_producto', $producto->id_producto)
            ->select('talla', 'stock')
            ->get();

        $colores = null;
        if ($producto->tipo_producto === 'ropa') {
            $colores = DB::table('producto_colores')
                ->where('id_producto', $producto->id_producto)
                ->pluck('color');
        }

        return response()->json([
            'tipo_producto' => $producto->tipo_producto,
            'talles' => $talles,
            'colores' => $colores
        ]);
    }
} 