<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends Controller
{
    //
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
    
    /**
     * Summary of resendOtp
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
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
