<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSenaApicolaAppAccess
{
    /**
     * Bloquea el panel operativo (admin/pasante) a quien solo tiene rol informativo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isSenaApicolaInfoViewer()) {
            return redirect()
                ->route('cefa.welcome')
                ->with('warning', 'Tu cuenta solo tiene acceso al contenido informativo. Si necesitas ingresar al panel, contacta a un administrador.');
        }

        return $next($request);
    }
}
