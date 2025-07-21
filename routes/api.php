<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthGoogleController;
use App\Http\Controllers\Api\BilleterieController;
use App\Http\Controllers\Api\CommentaireController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FavoriController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrganisateurEventController;
use App\Http\Controllers\Api\OrganisateurStatController;
use App\Http\Controllers\Api\OrganisateurTicketsController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PlansSouscriptionsController;
use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\ScannerController;
use App\Http\Controllers\Api\SouscriptionController;
use App\Http\Controllers\Api\SuiviController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\utilisateur\TagController;
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

// Route::post('/test-upload', [TestController::class, 'testcloudinary']);



Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');  //api/auth/google/callback
Route::post('/auth/login/scanneurs', [ScannerController::class, 'loginScanneur']); 
Route::get('auth/google', [AuthGoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/manuel', [AuthGoogleController::class, 'manuelredirectToGoogle']);
Route::get('auth/google/callback', [AuthGoogleController::class, 'handleGoogleCallback']);
Route::post('auth/google/callback/manuel', [AuthGoogleController::class, 'GoogleCallbackmanuel']);
Route::post('/auth/login/otp', [AuthController::class, 'connexionByOtp']);

//    *****  email et modifications/validations  *****

Route::post('/auth/verifyotp', [VerifyEmailController::class, 'verifyOtp']);
Route::post('/auth/resendotp', [VerifyEmailController::class, 'resendOtp']);

//    *****  email et update password/validations  *****
Route::post('/auth/password/sendotp', [PasswordResetController::class, 'sendResetOtp']);
Route::post('/auth/password/resetotp', [PasswordResetController::class, 'ResetOtp']);

Route::middleware(['auth:sanctum',  'verified'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/me', [UtilisateurController::class,'me']);
    Route::post('/me/update', [UtilisateurController::class,'update']); 
    Route::get('/auth/me', [UtilisateurController::class, 'connectedUser'])->name('user.connected');
    Route::get('/home/nearEvents', [EventController::class, 'getEventsNear']); // getEventsNear
    Route::get('/home/nearEvents/date', [EventController::class, 'getNearbyEventsWithDate']);
    Route::get('/home/sugestions/tag', [TagController::class, 'getRecommendedEvents']);

}); 
// ****  home page  *****

// Evenements
Route::get('/home/events', [EventController::class, 'search_2']);
Route::get('/home/search/tire', [EventController::class, 'searchTire']);
Route::get('/home/search/description', [EventController::class, 'searchDesc']);
Route::get('/home/search/lieu', [EventController::class, 'searchLieu']);
Route::get('/home/search/date', [EventController::class, 'searchDate']);
Route::get('/home/featured', [EventController::class, 'featured']);
Route::get('/home/upcoming', [EventController::class, 'upcoming']);
Route::get('/home/popular', [EventController::class, 'popular']);
Route::get('/home/categories', [EventController::class, 'search'])->name('search');
Route::get('/home/stats', [EventController::class, 'stat']);// pas encore fait
Route::get('/home/orgaEvent', [EventController::class, 'byOrganisateur']);

//     ***** test ***** 
// Route::post('/events/{eventId}/scan', [BilleterieController::class, 'verifierBillet']); //
Route::post('/events/{eventId}/scan', [TestController::class, 'testScann']);

Route::prefix('test') ->group(function () {
Route::post('/coudinary/upload', [TestController::class, 'storeImage']);
Route::get('/coudinary/{id}/image-qr', [TestController::class, 'showImageWithQR']);
// Route::get('/coudinary/{id}/image-qr', [TestController::class, 'showImageWithQR']);   //   showImageWithQR
Route::post('/events/ticket/{ticketId}', [TestController::class, 'update_Ticket']); //
Route::get('/events/billet/{billetId}', [TestController::class, 'getTicketData']); //  
Route::post('/public/upload', [TestController::class, 'storeImageinPublic']); //  storeImageinPublic
Route::get('/public/billet/{billetId}', [TestController::class, 'getTicketPublic']); //  


Route::post('/store', [TestController::class, 'store']);
Route::get('/{id}/show', [TestController::class, 'show']);

});


// Route::apiResource('events', EventController::class);
Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{eventId}', [EventController::class, 'show']);
Route::delete('/events/{eventId}', [EventController::class, 'destroy']);
Route::post('/events/{eventId}', [EventController::class, 'update']);




//     *****  billeterie  *****
// Route::get('/paiement/callback', [BilleterieController::class, 'callback'])->name('paiement.callback');
Route::post('/billet/webhook', [BilleterieController::class, 'webhookBillet']);
Route::middleware(['auth:sanctum', 'token.expiry', 'verified'])->group(function () {
        //billet/payer
    Route::post('/billet/payer', [BilleterieController::class, 'payer']);
    Route::get('/billet/userIndex', [BilleterieController::class, 'userIndexbillets']);

    Route::get('/welcome', function () { return view('welcome');
    });
});

//    *****  utilisateur  *****

    Route::get('/users/{userId}', [UtilisateurController::class, 'showUser'])->name('users.show');





//    *****  event  *****
// Route::middleware('auth:sanctum')->group(function () {
 
// });
//    *****  commentaire  *****
Route::middleware(['auth:sanctum',  'verified'])->group(function () {
    Route::get('/evenements/{eventId}/commentaires', [CommentaireController::class, 'index']);
    Route::post('/evenements/{eventId}/commentaires', [CommentaireController::class, 'store']);
    Route::post('/evenements/{commentId}/modifier', [CommentaireController::class, 'update']);
    Route::delete('/evenements/commentaires/{commentId}', [CommentaireController::class, 'destroy']);
});



//    *****  profil  *****

    
    
    //    *****  abonnement  *****
    Route::middleware(['auth:sanctum', ])->group(function () {
        Route::get('/favoris', [FavoriController::class, 'index']);
        Route::post('/favoris/{eventId}', [FavoriController::class, 'store']);
        Route::delete('/favoris/{eventId}', [FavoriController::class, 'destroy']);
    });

    //    *****  notifications  *****

     Route::middleware(['auth:sanctum', ])->group(function () {
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{notId}/mark-as-read', [NotificationController::class, 'markAsRead']);
    
    Route::get('/events/billets/{billetId}', [BilleterieController::class, 'generateBilletImage']);


}); 
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('/notifications', function () {
    //         return response()->json(auth()->user()->unreadNotifications);
    // });
   
    //     Route::post('/notifications/mark-as-read', function () {
    //     auth()->user()->unreadNotifications->markAsRead();
    //     return response()->json(['message' => 'Notifications marquées comme lues.']);
    // });
    // });
    Route::middleware(['auth:sanctum', ])->prefix('suivis')->group(function () {
    Route::get('/', [SuiviController::class, 'index']);
    Route::post('/suivre/{userId}', [SuiviController::class, 'suivre']);
    Route::delete('/ne-plus-suivre/{userId}', [SuiviController::class, 'nePlusSuivre']);
    

    //   ***** billeterie  *****


});

    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('suivre/index', [SuiviController::class, 'index'])->name('index.suivis');
    //     Route::post('/suivre/{organisateurId}', [SuiviController::class, 'suivre'])->name('suivre.organisateur');
    //     Route::delete('/suivre/{organisateurId}', [SuiviController::class, 'nePlusSuivre'])->name('neplus.suivre.organisateur');

    // });

    //    *****  abonnement  *****
    Route::post('/souscriptions/webhook', [SouscriptionController::class, 'souscriptionWebhook']);

    Route::middleware(['auth:sanctum',  'verified'])->prefix('souscriptions')->group(function () {
        Route::get('/profil/mon_abonnement', [SouscriptionController::class, 'monAbonnement']);
        Route::get('/plans', [SouscriptionController::class, 'plans']);
        Route::post('/', [SouscriptionController::class, 'paiementsouscrire']);
        // Route::post('/webhook', [SouscriptionController::class, 'webhooksouscription']);
        Route::get('/statut', [SouscriptionController::class, 'statut']);
        Route::get('/history', [SouscriptionController::class, 'historique']);
    });
// ***** organisateur
Route::get('/organisateur/{organisateurId}/events', [OrganisateurEventController::class, 'indexEventOrgaID']);
Route::get('/events/{eventId}/ticket', [OrganisateurTicketsController::class, 'indexTicketsEvent']);
Route::get('/events/ticket/{ticketId}', [OrganisateurTicketsController::class, 'showTicket']);

Route::prefix('organisateur')->middleware(['auth:sanctum',  'organisateur', 'souscription.active'])->group(function(){

        // Événements
        Route::get('/events', [OrganisateurEventController::class, 'index']); 
        Route::get('/ticket', [OrganisateurTicketsController::class, 'index']);
        Route::get('/events/{eventId}', [OrganisateurEventController::class, 'show']);
        

            
    
       
        Route::middleware( 'souscription.active')->group(function(){
            // Événements
            Route::post('/events', [OrganisateurEventController::class, 'store']); //
            Route::post('/events/{eventId}', [OrganisateurEventController::class, 'update']); //
            Route::delete('/events/{eventId}', [OrganisateurEventController::class, 'destroy']); //

            // tikets
            Route::post('/events/{eventId}/ticket', [OrganisateurTicketsController::class, 'addTicket']); //
            Route::post('/events/ticket/{ticketId}', [OrganisateurTicketsController::class, 'updateTicket']); //
            Route::delete('/events/ticket/{ticketId}', [OrganisateurTicketsController::class, 'destroyTicket']); //

            // Billets & Participants
            Route::get('/events/{eventId}/billets', [BilleterieController::class, 'eventBillets']); //
            Route::get('/events/{eventId}/participants', [BilleterieController::class, 'eventParticipants']); //
            // Route::get('/events/{eventId}/billets/scan', [BilleterieController::class, 'verifierBillet']); //
            Route::post('/events/{eventId}/billets/scan', [BilleterieController::class, 'verifierBillet']); //

            // ***** scanneurs *****
            Route::prefix('scanneurs')->group(function(){
                
            Route::get('/index', [ScannerController::class, 'indexorganisateurScanneurs'])->name('orga.scanner.index'); 
            Route::get('/{scanneurId}', [ScannerController::class, 'showScanneur']); 
            Route::post('/{eventId}', [ScannerController::class, 'generateScanneurs']);
            Route::put('/{scanneurId}', [ScannerController::class, 'updateScanneurs']);
            // 📋 Liste des scanneurs d’un événement (optionnel si tu veux)
            Route::get('/{eventId}', [ScannerController::class, 'indexScanneurs']);
            Route::delete('/{scanneurId}', [ScannerController::class, 'deleteScanneur']);


        });
        });

    
    // Route::post('/souscription', [SouscriptionController::class, 'store']);
    // Route::get('/souscription/statut', [SouscriptionController::class, 'status']);
        
    // Souscription
    // Dashboard
    Route::get('/statistiques', [OrganisateurStatController::class, 'organisateurStats']);
  
    // ****  orga page  ***** 
    // Route::post('/scan_billet', [BilleterieController::class, 'verifierBillet']);
    Route::get('/home/featured', [EventController::class, 'featured']);



});
// ***** groupe pour les scanneurs uniquement *****
Route::middleware(['auth:sanctum',  'scanneur'])->prefix('scanneur')->group(function () {
    Route::get('/billets', [ScannerController::class, 'listBilletsScanneur']);
    Route::get('/event/', [ScannerController::class, 'listEventsScanneur']);
    // Route::post('/scan-billet/{eventId}', [ScannerController::class, 'scannerBillet']); 
    Route::post('/scan-billet', [ScannerController::class, 'scannerBillet']);
    Route::get('/mes-billets-scannes', [ScannerController::class, 'mesbilletsScannés']);
});


 //  ***** admin
Route::prefix('admin')->middleware(['auth:sanctum',  'verified', 'admin'])->group(function () {
        Route::get('/user/index', [AuthController::class, 'index'])->name('user.index');
        Route::get('/user/actifs', [AuthController::class, 'usersActifs']);        



         // Utilisateurs
    Route::get('/users', [AdminController::class, 'indexUsers']);
    Route::get('/users/{userId}', [AdminController::class, 'showUser']);
    Route::post('/user/{userId}/update', [AdminController::class, 'usersupdate']);

    Route::post('/users/{userId}/ban', [AdminController::class, 'banUser']);
    Route::post('/users/{userId}/unban', [AdminController::class, 'unbanUser']);
    Route::delete('/users/{userId}', [AdminController::class, 'destroyUser']);

    // // Événements
    Route::get('/evenements', [AdminController::class, 'allEvents']);
    Route::get('/evenements/{eventId}', [AdminController::class, 'showEvent']);
    // Route::post('/evenements/{eventId}/valider', [AdminController::class, 'validateEvent']);
    // Route::post('/evenements/{eventId}/rejeter', [AdminController::class, 'rejectEvent']);
    Route::delete('/evenements/{eventId}', [AdminController::class, 'deleteEvent']);

    // // Souscriptions
    Route::get('/souscriptions', [AdminController::class, 'allSouscriptions']);
    Route::get('/souscris', [AdminController::class, 'souscris']);

    // // Plans Souscriptions
    Route::post('/souscriptions/plan', [PlansSouscriptionsController::class, 'addPlan']);
    Route::post('/souscriptions/plan/{planId}', [PlansSouscriptionsController::class, 'updatePlan']);
    Route::delete('/souscriptions/plan/{planId}', [PlansSouscriptionsController::class, 'deletePlan']);

    // Route::put('/souscriptions/{id}/valider', [AdminController::class, 'validateSouscription']);
    // // scanneur
    Route::get('/organisateur/{orgaId}/scanneurs', [AdminController::class, 'organisateurScanneurs']); 



    // // Dashboard
    // Route::get('/statistiques', [StatController::class, 'adminStats']);

    });

Route::post('/test_upload', [TestController::class, 'testcloudinary']);

    
                            
    



// Route::middleware(['auth:sanctum', 'verified'])->get('/test-auth', function (Request $request) {
//     return response()->json([
//         'message' => 'Vous êtes connecté !',
//         'user' => $request->user()
//     ]);
// });
// Route::post('testcloudinary', [TestController::class, 'testcloudinary']);
















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








