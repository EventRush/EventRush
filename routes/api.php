<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BilleterieController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FavoriController;
use App\Http\Controllers\Api\OrganisateurEventController;
use App\Http\Controllers\Api\OrganisateurStatController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\SouscriptionController;
use App\Http\Controllers\Api\SuiviController;
use App\Http\Controllers\Api\SuiviContronller;
use App\Http\Controllers\Api\UtilisateurController;
use App\Http\Controllers\Api\VerifyEmailController;
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


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/me', [UtilisateurController::class, 'connectedUser'])->name('user.connected');

//    *****  email et modifications/validations  *****

Route::post('/auth/verifyotp', [VerifyEmailController::class, 'verifyOtp']);
Route::post('/auth/resendotp', [VerifyEmailController::class, 'resendOtp']);

//    *****  email et modifications/validations  *****
Route::post('/auth/password/sendotp', [PasswordResetController::class, 'sendResetOtp']);
Route::post('/auth/password/resetotp', [PasswordResetController::class, 'ResetOtp']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        
    Route::get('/me', [UtilisateurController::class,'me']);
    Route::post('/me/update', [UtilisateurController::class,'update']); 
    
    Route::get('/auth/me', [UtilisateurController::class, 'connectedUser'])->name('user.connected');

}); 
// ****  home page  ***** 
Route::get('/home/events', [EventController::class, 'search_2']);
Route::get('/home/featured', [EventController::class, 'featured']);
Route::get('/home/upcoming', [EventController::class, 'upcoming']);
Route::get('/home/categories', [EventController::class, 'search'])->name('search');
Route::get('/home/stats', [EventController::class, 'stat']);// pas encore fait
Route::get('/home/orgaEvent', [EventController::class, 'byOrganisateur']);


// Route::get('/search', [EventController::class, 'search'])->name('search');



// Route::post('/billet/achat', [BilleterieController::class, 'achat'])->name('billet.achat');

Route::get('/paiement/callback', [BilleterieController::class, 'callback'])->name('paiement.callback');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
        //billet/payer
    Route::post('/billet/payer', [BilleterieController::class, 'payer']);

    Route::get('/welcome', function () { return view('welcome');
    });
});

//    *****  utilisateur  *****






//    *****  event  *****
// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::post('/events/{event}', [EventController::class, 'update']);

// });
//    *****  commentaire  *****
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/evenements/{event}/commentaires', [CommentaireController::class, 'index']);
    Route::post('/evenements/{event}/commentaires', [CommentaireController::class, 'store']);
    Route::post('/evenements/{commenT}/modifier', [CommentaireController::class, 'update']);
    Route::delete('/evenements/commentaires/{id}', [CommentaireController::class, 'destroy']);
});



//    *****  profil  *****

    //    *****  abonnement  *****
    Route::middleware(['auth:sanctum', 'verified'])->prefix('souscriptions')->group(function () {
        Route::get('/profil/mon_abonnement', [SouscriptionController::class, 'monAbonnement']);
        Route::get('/plans', [SouscriptionController::class, 'plans']);
        Route::post('/', [SouscriptionController::class, 'paiementsouscrire']);
        Route::post('/backsous/webhook', [SouscriptionController::class, 'webhooksouscription']);
        Route::get('/statut', [SouscriptionController::class, 'statut']);
        Route::get('/history', [SouscriptionController::class, 'historique']);
    });
    
    //    *****  abonnement  *****
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/favoris', [FavoriController::class, 'index']);
        Route::post('/favoris/{eventId}', [FavoriController::class, 'store']);
        Route::delete('/favoris/{eventId}', [FavoriController::class, 'destroy']);
    });

    //    *****  notifications  *****
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', function () {
            return response()->json(auth()->user()->unreadNotifications);
    });
        Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications marquées comme lues.']);
    });
    });

    Route::middleware('auth')->group(function () {
        Route::get('suivre/index', [SuiviController::class, 'index'])->name('index.suivis');
        Route::post('/suivre/{organisateurId}', [SuiviController::class, 'suivre'])->name('suivre.organisateur');
        Route::delete('/suivre/{organisateurId}', [SuiviController::class, 'nePlusSuivre'])->name('neplus.suivre.organisateur');

    });

// ***** organisateur
Route::prefix('organisateur')->middleware(['authsanctum', 'role:organisateur', 'souscription.active'])->group(function(){

        // Événements
        Route::get('/events', [OrganisateurEventController::class, 'index']);
        Route::post('/events', [OrganisateurEventController::class, 'store']);
        Route::get('/events/{id}', [OrganisateurEventController::class, 'show']);
        Route::put('/events/{id}', [OrganisateurEventController::class, 'update']);
        Route::delete('/events/{id}', [OrganisateurEventController::class, 'destroy']);
    
        // Billets & Participants
        Route::get('/events/{id}/billets', [BilleterieController::class, 'eventBillets']);
        Route::get('/events/{id}/participants', [BilleterieController::class, 'eventParticipants']);
    
        // Souscription
        Route::post('/souscription', [SouscriptionController::class, 'store']);
        Route::get('/souscription/statut', [SouscriptionController::class, 'status']);
    
        // Dashboard
        Route::get('/statistiques', [OrganisateurStatController::class, 'organisateurStats']);
  
        // ****  orga page  ***** 
        Route::post('/scan_billet', [BilleterieController::class, 'verifierBillet']);
        Route::get('/home/featured', [EventController::class, 'featured']);

});


 //  ***** admin
    Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
        Route::get('/user/index', [AuthController::class, 'index'])->name('user.index');
        Route::get('/user/actifs', [AuthController::class, 'usersActifs']);


         // Utilisateurs
    // Route::get('/utilisateurs', [AdminController::class, 'indexUsers']);
    // Route::get('/utilisateurs/{id}', [AdminController::class, 'showUser']);
    // Route::put('/utilisateurs/{id}/ban', [AdminController::class, 'banUser']);
    // Route::put('/utilisateurs/{id}/unban', [AdminController::class, 'unbanUser']);
    // Route::delete('/utilisateurs/{id}', [AdminController::class, 'destroyUser']);

    // // Événements
    // Route::get('/evenements', [AdminController::class, 'allEvents']);
    // Route::get('/evenements/{id}', [AdminController::class, 'showEvent']);
    // Route::put('/evenements/{id}/valider', [AdminController::class, 'validateEvent']);
    // Route::put('/evenements/{id}/rejeter', [AdminController::class, 'rejectEvent']);
    // Route::delete('/evenements/{id}', [AdminController::class, 'deleteEvent']);

    // // Souscriptions
    // Route::get('/souscriptions', [AdminController::class, 'allSouscriptions']);
    // Route::put('/souscriptions/{id}/valider', [AdminController::class, 'validateSouscription']);

    // // Dashboard
    // Route::get('/statistiques', [StatController::class, 'adminStats']);

    });
    
                            
    



Route::middleware(['auth:sanctum', 'verified'])->get('/test-auth', function (Request $request) {
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








