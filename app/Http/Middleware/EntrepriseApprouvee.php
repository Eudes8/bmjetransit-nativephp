<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EntrepriseApprouvee
{
    /**
     * Verifie que l'entreprise de l'utilisateur est approuvee par BMJE.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->entreprise || $user->entreprise->statut !== 'approuvee') {
            return redirect()->route('espace.en_attente');
        }

        return $next($request);
    }
}
