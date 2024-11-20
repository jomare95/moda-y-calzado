<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('123456'),
            'rol' => 'admin',
            'estado' => true
        ]);
    }
} 