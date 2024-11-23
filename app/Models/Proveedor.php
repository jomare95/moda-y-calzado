<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    
    public $timestamps = true;
    
    protected $fillable = [
        'razon_social',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'telefono',
        'email',
        'contacto_nombre',
        'contacto_telefono',
        'estado'
    ];

    // Relación con compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor');
    }
} 