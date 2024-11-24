<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoTalle extends Model
{
    protected $table = 'producto_talles';
    
    public $timestamps = false;
    
    public $incrementing = false;
    protected $primaryKey = ['id_producto', 'talla'];
    
    protected $fillable = [
        'id_producto',
        'talla',
        'stock'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
} 