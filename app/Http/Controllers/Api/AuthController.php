<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpMail;

class AuthController extends Controller
{

    
    public function register(Request $request)
{
    $request->validate([
        'nom' => 'required',
        'email' => 'required|email|unique:utilisateurs',
        'password' => 'required|min:6|confirmed',
    ]);

    $otp = rand(100000, 999999); // Générer un code OTP
    $utilisateur = Utilisateur::create([
        'nom' => $request->nom,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(10) // Expiration du code après 10 minutes
    ]);

    Mail::to($utilisateur->email)->send(new otpMail($otp));

    return response()->json(['message' => 'Inscription réussie, vérifiez votre email pour le code OTP.']);
}


    public function login(Request $request){
        $request->validate([
            'email'=>'required|string|email',     
            'password'=>'required|string|min:6|',
        ]);
        $utilisateur =  Utilisateur::where('email', $request->email)->first();
        if (!$utilisateur || !Hash::check($request->password, $utilisateur->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        if (!$utilisateur->hasVerifiedEmail()) {
            return response()->json(['message' => 'Veuillez vérifier votre email'], 403);
        }
    
        Auth::login($utilisateur);
        $token = $utilisateur->createToken('auth_token')->plainTextToken;
        
    
        return response()->json([
            'access_token' => $token, 
            'token_type' => 'Bearer',
            'message'=> 'Utilisateur connecté',
            'role' => $utilisateur->role, 

        ]);        
       
    }
    public function connexionByOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$utilisateur) {
            return response()->json(['message' => 'Code OTP ou email invalide.'], 401);
        }

        if (!$utilisateur->hasVerifiedEmail()) {
            return response()->json(['message' => 'Votre mail n\'a pas encore été vérifié '], 403);
        }

        if (Carbon::now()->gt($utilisateur->otp_expires_at)) {
            return response()->json(['message' => 'Code OTP expiré.'], 401);
        }

        // Invalider l’OTP après utilisation
        $utilisateur->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => $utilisateur->email_verified_at ?? Carbon::now(),
        ]);

        // Connexion + génération du token
        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie 🎉',
            'utilisateur' => $utilisateur,
            'token' => $token
        ]);
    }
    /**
     *  logout
     * 
     */
    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Déconnexion réussie']);
}

    public function index()
        {
            //
            $utilisateurs = Utilisateur::where('role', '!=', 'admin')->orderBy('created_at', 'asc')->get();

            return response()->json([
                'message' => 'Voici les utilisateurs ',
                'utilisateurs' => $utilisateurs
            ],
        200);
        }

        public function usersActifs()
{
    $utilisateurs = Utilisateur::where('last_seen_at', '>=', now()->subMinutes(5))->get();

    return response()->json([
        'en_ligne' => $utilisateurs->count(),
        'utilisateurs' => $utilisateurs
    ]);
}

}


