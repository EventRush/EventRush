<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\utilisateur;
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
        'password' => 'required|min:6',
        'confirm' => 'required|min:6',
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
//     $request->validate([
//         'nom' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:utilisateurs',
//         'password' => 'required|string|min:8|confirmed',
//     ]);

//     // Créer l'utilisateur
//     $utilisateur = Utilisateur::create([
//         'nom' => $request->nom,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//     ]);

//     // Envoi automatique de l'email de vérification via la notification par défaut
//     $utilisateur->sendEmailVerificationNotification();

//     return response()->json([
//         'message' => 'Un email de confirmation a été envoyé. Veuillez vérifier votre boîte mail.']);
// }

    // code 1 pour

    // public function register(Request $request){
    //     $request->validate([
    //         'nom'=>'required|string|max:255',     
    //         'email'=>'required|string|email|max:255|unique:utilisateurs',     
    //         'password'=>'required|string|min:8|confirmed',
    //     ]);
    //     $utilisateur =  Utilisateur::create([
    //         'nom' => $request->nom,     
    //         'email' => $request->email,     
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Envoi automatique de l'email de confirmation
    //     event(new Registered($utilisateur));

    //     $token = $utilisateur->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'user' => $utilisateur,
    //         'token' => $token,
    //         'message'=> 'Inscription réussie. Vérifiez votre email.'
    //     ], 201);
    // }

//     public function register(Request $request)
// {
//     $request->validate([
//         'nom' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:Utilisateurs',
//         'password' => 'required|string|min:8|confirmed',
//     ]);

//     // Créer un utilisateur temporairement mais ne pas le sauvegarder
//     $utilisateur = new Utilisateur([
//         'nom' => $request->nom,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//     ]);

//     // Générer un token de vérification
//     $token = Str::random(64);
//     DB::table('email_verifications')->insert([
//         'email' => $request->email,
//         'token' => $token,
//         'created_at' => now()
//     ]);

//     // Envoyer l’email de vérification
//     Mail::to($request->email)->send(new VerifyEmail($token));

//     return response()->json([
//         'message' => 'Un email de confirmation a été envoyé. Veuillez vérifier votre boîte mail.'
//     ]);
// }


// public function register(Request $request)
// {
//     $request->validate([
//         'nom' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:8|confirmed',
//     ]);

//     // Créer un utilisateur en base de données sans valider l'email
//     $utilisateur = Utilisateur::create([
//         'nom' => $request->nom,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//     ]);

//     // Générer un token de vérification
//     $token = Str::random(64);
//     DB::table('email_verifications')->insert([
//         'email' => $request->email,
//         'token' => $token,
//         'created_at' => now()
//     ]);

//     // Envoyer l'email de vérification avec le token
//     Mail::to($request->email)->send(new VerifyEmail($token));

//     return response()->json([
//         'message' => 'Un email de confirmation a été envoyé. Veuillez vérifier votre boîte mail.'
//     ]);
// }

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
    
        $token = $utilisateur->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token, 
            'token_type' => 'Bearer',
            'message'=> 'Utilisateur connecté'
        ]);
    
        
            
       
    }
    /**
     *  logout
     * 
     */
    public function logout(Request $request)
{
    $request->utilisateur()->tokens()->delete();

    return response()->json(['message' => 'Déconnexion réussie']);
}

}
