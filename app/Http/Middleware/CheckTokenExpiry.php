<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = Auth::user();
 if ($user) {
        $token = $user->currentAccessToken();

        if ($token instanceof \Laravel\Sanctum\TransientToken) {
            // On ignore les tokens de session (utilisation navigateur classique)
            return $next($request);
        }

        // Sinon, on est sur un PersonalAccessToken → expiration manuelle
        if ($token && $token->created_at->diffInMinutes(now()) > 5) {
            $token->delete();
            return response()->json(['message' => 'Session expirée. Veuillez vous reconnecter.'], 401);
        }
    }
        return $next($request);

    }
}
