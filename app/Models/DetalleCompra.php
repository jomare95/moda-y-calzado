<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';
    protected $primaryKey = 'id_detalle_compra';

    protected $fillable = [
        'id_compra',
        'id_producto',
        'cantidad',
        'precio_compra',
        'subtotal'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
} 