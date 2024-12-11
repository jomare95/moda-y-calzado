<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        dd('Middleware admin ejecutado'); // Esto debería detener la ejecución y mostrar el mensaje
        // Verifica si el usuario autenticado es un administrador
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Redirige si no es administrador
        return redirect('/'); // Cambia la ruta según sea necesario
    }
}