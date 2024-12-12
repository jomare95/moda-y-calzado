<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Admin (admin@sistema) tiene acceso total
        if ($user->rol === '') {
            return $next($request);
        }

        // Verificar roles específicos
        foreach ($roles as $role) {
            if ($user->rol === $role) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
} 