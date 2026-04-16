<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Verifie que l'utilisateur connecte a le role requis.
     * Usage dans les routes : ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->type, $roles)) {
            abort(403, 'Acces non autorise.');
        }

        return $next($request);
    }
}
