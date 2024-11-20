<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    
    protected $fillable = [
        'codigo',
        'nombre',
        'id_categoria',
        'id_marca',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'talla',
        'color',
        'material',
        'genero',
        'imagen',
        'estado'
    ];

    protected $guarded = ['id_producto'];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'estado' => 'boolean'
    ];

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto');
    }

    // Mutador para asegurar que el código esté en mayúsculas
    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper($value);
    }

    // Accesorio para la URL de la imagen
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return Storage::url($this->imagen);
        }
        return asset('img/no-image.png');
    }

    // Definir la relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function colores()
    {
        return $this->hasMany(ProductoColor::class, 'id_producto');
    }

    public function talles()
    {
        return $this->hasMany(ProductoTalle::class, 'id_producto');
    }

    // Opcional: Agregar un mutador para el estado
    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'Activo' : 'Inactivo';
    }

    // Opcional: Agregar un mutador para el tipo
    public function getTipoFormateadoAttribute()
    {
        return ucfirst(strtolower($this->tipo));
    }
} 