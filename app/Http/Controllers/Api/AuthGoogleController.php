<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{
    //

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = Utilisateur::where(['email' => $googleUser->getEmail()])->first();
            if (!$googleUser) {
                return response()->json(['error' => 'Impossible de récupérer les informations utilisateur.'], 500);
            }
    

            if($user) {
                if(!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $user->avatar ?? $googleUser->getAvatar(),

                    ]);
                }
            } else {

            $user = Utilisateur::Create([
                'email' => $googleUser->getEmail(),
                'nom' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt($googleUser->getEmail()), // Mot de passe temporaire
            ]);
        }

            Auth::login($user);

            // Génération du token avec Laravel Sanctum
            $token = $user->createToken('GoogleLogin')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message'=> 'Utilisateur connecté'
            ], 200);
        

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la connexion Google.',
                'details' => $e->getMessage()
            ],500);
    }
    }
}
