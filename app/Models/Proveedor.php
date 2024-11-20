<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    
    // Solo tiene created_at, no updated_at
    const UPDATED_AT = null;

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

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relación con compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor', 'id_proveedor');
    }
} 