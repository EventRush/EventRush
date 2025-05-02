<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
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

        if ($utilisateur->role !== 'Admin'){
            return $next($request);
        }
        
        return $next($request);
    }
}
