<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    //
    public function sendResetOtp(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $utilisateur = Utilisateur::where('email', $request->email)->first();

    if (!$utilisateur) {
        return response()->json(['message' => 'Aucun compte trouvé avec cet email.'], 404);
    }

    $otp = rand(100000, 999999);
    $utilisateur->update([
        'otp' => $otp,
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Mail::to($utilisateur->email)->send(new OtpMail($otp)); // Tu peux réutiliser le même OtpMail

    return response()->json(['message' => 'Un code OTP de réinitialisation a été envoyé par email.']);
}

public function ResetOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required',
    ]);

    $utilisateur = Utilisateur::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

    if (!$utilisateur) {
        return response()->json(['message' => 'Code OTP invalide.'], 400);
    }

    if (now()->greaterThan($utilisateur->otp_expires_at)) {
        return response()->json(['message' => 'Code OTP expiré.'], 400);
    }

    return response()->json(['message' => 'OTP validé. Vous pouvez réinitialiser votre mot de passe.']);
}


    // public function sendResetLink(Request $request){
    //     $request->validate(['email' => 'required|email|exists:utilisateurs,email']);
    //     $status = Password::sendResetLink($request->only('email'));

    //     return $status === Password::RESET_LINK_SENT
    //     ? response()->json(['message' => 'Email de réinitialisation envoyé'])
    //     : response()->json(['message' => 'Erreur lors de l\'envoi de l\'email.'], 400);

    // }
    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:utilisateurs,email',
        'token' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($utilisateur, $password) {
            $utilisateur->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Mot de passe réinitialisé avec succès.'])
        : response()->json(['message' => 'Erreur lors de la réinitialisation.'],400);
}
}
