<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSouscriptionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = $request->user();

        if (!$utilisateur) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        if ($utilisateur->role !== 'organisateur'){
            return $next($request);
        }
        $souscription = $utilisateur->souscriptionActive();
        if (!$souscription || !$souscription->estActive()) {
            return response()->json([
                'message' => 'Votre souscription est expirée ou inactive.'
            ], 403);
        }
        return $next($request);

    }
}
