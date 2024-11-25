<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'id_compra';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'id_proveedor',
        'numero_comprobante',
        'fecha_compra',
        'tipo_comprobante',
        'subtotal',
        'iva',
        'total',
        'estado',
        'notas'
    ];

    protected $dates = [
        'fecha_compra',
        'created_at',
        'updated_at'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }
} 