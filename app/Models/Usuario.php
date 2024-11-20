<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
        'ultimo_login'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ultimo_login' => 'datetime',
        'estado' => 'boolean',
    ];

    public function movimientosCaja()
    {
        return $this->hasMany(MovimientoCaja::class, 'id_usuario');
    }

    // Método para verificar roles
    public function hasRole($role)
    {
        return $this->rol === $role;
    }

    // Método para verificar si el usuario está activo
    public function isActive()
    {
        return $this->estado === 1;
    }
} 