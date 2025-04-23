<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends Controller
{

    /**
     * Summary of verifyOtp
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6',
    ]);

    $utilisateur = Utilisateur::where('email', $request->email)->where('otp', $request->otp)->first();

    if (!$utilisateur) {
        return response()->json(['message' => 'Code OTP invalide.'], 400);
    }

    if (Carbon::now()->gt($utilisateur->otp_expires_at)) {
        return response()->json(['message' => 'Code OTP expiré.'], 400);
    }

    $utilisateur->update([
        'email_verified_at' => Carbon::now(),
        'otp' => null,
        'otp_expires_at' => null
    ]);

    return response()->json(['message' => 'Email confirmé avec succès. vous pouvez desormais vous connecter via la page de login.']);
}
public function resendOtp(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $utilisateur = Utilisateur::where('email', $request->email)->first();
    if (!$utilisateur) {
        return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
    }

    // Générer un nouveau OTP
    $otp = rand(100000, 999999);
    $utilisateur->update([
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(10),
    ]);

    Mail::to($utilisateur->email)->send(new OtpMail($otp));

    return response()->json(['message' => 'Un nouveau code OTP a été envoyé.']);
    }

}

    // public function verify($token)
    // {
    //     // Chercher le token dans la table email_verifications
    //     $verification = DB::table('email_verifications')->where('token', $token)->first();

    //     if (!$verification) {
    //         return response()->json(['message' => 'Lien invalide ou expiré'], 400);
    //     }

    //     // Vérifier si l'utilisateur existe dans la table utilisateurs
    //     $utilisateur = Utilisateur::where('email', $verification->email)->first();
    //     if (!$utilisateur) {
    //         return response()->json(['message' => 'Utilisateur introuvable.'], 404);
    //     }

    //     // Mettre à jour la colonne email_verified_at
    //     $utilisateur->email_verified_at = now();
    //     $utilisateur->save();

    //     // Supprimer l'entrée de vérification d'email
    //     DB::table('email_verifications')->where('email', $verification->email)->delete();

    //     return response()->json(['message' => 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.']);
    // }

//     public function verify($token)
// {
//     // Trouver l'utilisateur avec le token de vérification
//     $utilisateur = Utilisateur::where('email_verification_token', $token)->first();

//     if (!$utilisateur) {
//         return response()->json(['message' => 'Lien invalide ou expiré'], 400);
//     }

//     // Marquer l'utilisateur comme vérifié
//     if (!$utilisateur->hasVerifiedEmail()) {
//         $utilisateur->markEmailAsVerified();
//         event(new Verified($utilisateur));
//     }

//     return response()->json(['message' => 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.']);
// }
    //
//     public function verify($token)
// {
//     $verification = DB::table('email_verifications')->where('token', $token)->first();

//     if (!$verification) {
//         return response()->json(['message' => 'Lien invalide ou expiré'], 400);
//     }

//     // Créer et sauvegarder l’utilisateur
//     $utilisateur = Utilisateur::create([
//         'nom' => 'Nom temporaire',
//         'email' => $verification->email,
//         'password' => Hash::make('password_temporaire'),
//         'email_verified_at' => now(),
//     ]);

//     // Supprimer le token après validation
//     DB::table('email_verifications')->where('email', $verification->email)->delete();

//     return response()->json(['message' => 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.']);
// }

// public function verify($token)
// {
//     $verification = DB::table('email_verifications')->where('token', $token)->first();

//     if (!$verification) {
//         return response()->json(['message' => 'Lien invalide ou expiré'], 400);
//     }

//     // Vérifier si l'utilisateur existe
//     $utilisateur = Utilisateur::where('email', $verification->email)->first();
//     if (!$utilisateur) {
//         return response()->json(['message' => 'Utilisateur introuvable.'], 404);
//     }

//     // Marquer l'utilisateur comme vérifié
//     $utilisateur->email_verified_at = now();
//     $utilisateur->save();

//     // Supprimer l'entrée de vérification d'email
//     DB::table('email_verifications')->where('email', $verification->email)->delete();

//     return response()->json(['message' => 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.']);
// }
