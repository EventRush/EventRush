<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BilleterieController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\SouscriptionController;
use App\Http\Controllers\Api\UtilisateurController;
use App\Http\Controllers\API\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/search', [EventController::class, 'search'])->name('search');
Route::get('/user/index', [AuthController::class, 'index'])->name('user.index');

// Route::post('/billet/achat', [BilleterieController::class, 'achat'])->name('billet.achat');

Route::get('/paiement/callback', [BilleterieController::class, 'callback'])->name('paiement.callback');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
        //billet/payer
    Route::post('/billet/payer', [BilleterieController::class, 'payer']);

    Route::get('/welcome', function () { return view('welcome');
    });
});

//    *****  utilisateur  *****
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [UtilisateurController::class,'me']);
    Route::post('/me/update', [UtilisateurController::class,'update']);   
});


//    *****  email et modifications/validations  *****

Route::post('/verifyotp', [VerifyEmailController::class, 'verifyotp']);
Route::post('/resendotp', [VerifyEmailController::class, 'resendotp']);


//    *****  event  *****
// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::post('/events/{event}', [EventController::class, 'update']);

// });

//    *****  profil  *****

    //    *****  abonnement  *****
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/profil/souscrire', [SouscriptionController::class, 'souscrire']);
        Route::get('/profil/mon_abonnement', [SouscriptionController::class, 'monAbonnement']);
    });



Route::middleware('auth:sanctum')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Vous êtes connecté !',
        'user' => $request->user()
    ]);
});













//    email et modifications/validations 

// confirmation email
// vérifier l'email quand l'utilisateur clique sur le lien reçu
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill(); // Valide l'email

//     return response()->json(['message' => 'Email vérifié avec succès.']);
// })->middleware(['signed'])->name('verification.verify');

// mot de passe oublé











// Envoyer un nouvel email de vérification (au cas où l'utilisateur ne l'a pas reçu)

// Route::post('/email/resend', function (Request $request) {
//     if ($request->user()->hasVerifiedEmail()) {
//         return response()->json(['message' => 'Email déjà vérifié.'], 400);
//     }

//     $request->user()->sendEmailVerificationNotification();
//     return response()->json(['message' => 'Email de vérification renvoyé.']);
// })->middleware('auth:sanctum');








