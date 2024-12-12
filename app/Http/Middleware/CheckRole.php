<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Para el caso de admin
        if ($role === 'Administrador') {
            if (auth()->user()->rol !== '' && auth()->user()->rol !== 'Administrador') {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
        }
        // Para otros roles
        else if (auth()->user()->rol !== $role) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
} 