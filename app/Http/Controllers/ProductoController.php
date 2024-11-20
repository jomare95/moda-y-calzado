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
        $categorias = Categoria::where('estado', 1)->orderBy('nombre')->get();
        $marcas = Marca::where('estado', 1)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('razon_social')->get();

        return view('productos.create', compact('categorias', 'marcas', 'proveedores'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'codigo' => 'required|unique:productos',
                'nombre' => 'required',
                'marca_option' => 'required|in:existente,nueva',
                'id_marca' => 'required_if:marca_option,existente|exists:marcas,id_marca',
                'marca_nueva' => 'required_if:marca_option,nueva|max:100',
                'id_categoria' => 'required|exists:categorias,id_categoria',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'colores' => 'required|array|min:1',
                'colores.*' => 'required|string|max:50',
                'talles' => 'required|array|min:1',
                'talles.*' => 'required|string|max:10',
                'stocks.*' => 'required|integer|min:0'
            ]);

            DB::beginTransaction();

            if ($request->marca_option === 'nueva') {
                $marca = Marca::create([
                    'nombre' => $request->marca_nueva,
                    'estado' => 1
                ]);
                $id_marca = $marca->id_marca;
            } else {
                $id_marca = $request->id_marca;
            }

            $producto = Producto::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'id_categoria' => $request->id_categoria,
                'id_marca' => $id_marca,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock,
                'stock_minimo' => $request->stock_minimo,
                'estado' => 1
            ]);

            foreach ($request->colores as $color) {
                if (!empty($color)) {
                    DB::table('producto_colores')->insert([
                        'id_producto' => $producto->id_producto,
                        'color' => $color
                    ]);
                }
            }

            foreach ($request->talles as $index => $talle) {
                if (!empty($talle)) {
                    DB::table('producto_talles')->insert([
                        'id_producto' => $producto->id_producto,
                        'talla' => $talle,
                        'stock' => $request->stocks[$index] ?? 0
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('productos.index')
                ->with('success', 'Producto creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear producto: ' . $e->getMessage());
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
            $request->validate([
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
                'estado' => $request->has('estado') ? 1 : 0
            ]);

            return redirect()->route('productos.index')
                ->with('success', 'Producto actualizado correctamente');
            
        } catch (\Exception $e) {
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
} 