<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'id_marca';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    // Desactivar timestamps si tu tabla no los tiene
    public $timestamps = false;

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_marca');
    }
} 