<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class ActualizarEmpleado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empleado:actualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la contraseña del empleado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $empleado = Usuario::where('email', 'empleado@sistema')->first();
        
        if (!$empleado) {
            $this->error('No se encontró el empleado');
            return;
        }

        $empleado->update([
            'password' => Hash::make('123456'),
            'estado' => 1
        ]);

        $this->info('Empleado actualizado correctamente');
    }
}
