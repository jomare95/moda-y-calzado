<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';
    
    protected $fillable = [
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_final',
        'estado',
        'observaciones',
        'user_id'
    ];

    protected $dates = [
        'fecha_apertura',
        'fecha_cierre'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
