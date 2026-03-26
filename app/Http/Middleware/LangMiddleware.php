<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class LangMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Establecer el idioma si está disponible en la sesión
        if (!empty(session('lang'))) {
            App::setLocale(session('lang'));
        }

        // Verificar el nombre de la ruta y el acceso a la autorización
        if (
            $request->route()->getName() !== '' &&
            strpos($request->route()->getName(), 'cefa.') !== 0 &&
            strpos($request->route()->getName(), 'senaapicola.') !== 0
        ) {
            // Obtener la acción de la ruta
            $action = $request->route()->getAction();

            if (is_string($action['uses'])) {
                $pos2 = strpos($action['uses'], 'Auth');
                if ($pos2 === false) {
                    // Si no contiene 'Auth', entonces verificar el acceso usando Gate
                    Gate::authorize('haveaccess', $request->route()->getName());
                }
            }
        }

        return $next($request);
    }
}
