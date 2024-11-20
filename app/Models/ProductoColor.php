<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoColor extends Model
{
    protected $table = 'producto_colores';
    public $timestamps = false;
    
    protected $fillable = ['id_producto', 'color'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
} 