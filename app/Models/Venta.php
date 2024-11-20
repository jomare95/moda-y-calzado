<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    
    protected $fillable = [
        'id_cliente',
        'id_usuario',
        'numero_comprobante',
        'fecha_venta',
        'tipo_comprobante',
        'tipo_pago',
        'subtotal',
        'iva',
        'impuestos',
        'total',
        'estado'
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'created_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente')->withDefault([
            'nombre' => 'Venta Libre',
            'tipo_documento' => '-',
            'numero_documento' => '-'
        ]);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_ventas', 'id_venta', 'id_producto')
            ->withPivot('cantidad', 'precio', 'subtotal');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function getEstadoColorAttribute()
    {
        return [
            'Completada' => 'bg-green-100 text-green-800',
            'Pendiente' => 'bg-yellow-100 text-yellow-800',
            'Anulada' => 'bg-red-100 text-red-800'
        ][$this->estado] ?? 'bg-gray-100 text-gray-800';
    }
} 