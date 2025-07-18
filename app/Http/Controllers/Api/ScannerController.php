<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Event;
use App\Models\EventScanneur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ScannerController extends Controller
{
    //
    
    // // generation auth
    public function generateScanneurs(Request $request, $eventId) { 
        $request->validate([ 
            'nombre' => 'required|integer|min:1' ]);

    $organisateur = Auth::user();
    $event = Event::findOrFail($eventId);

    // $limite = $organisateur->abonnement?->scanneur_limit ?? 5;
    $souscription = $organisateur->souscriptionActive();

    if(!$souscription) return response()->json(['error' => "Veuillez réactiver votre souscription"]);

    if ($event->date_fin < now()) return response()->json(['error' => "L'événement est terminé."], 403);

    if($event->utilisateur_id != $organisateur->id) return response()->json(['error' => "Vous ne pouvez pas créer de scanneur(s) pour cet événement."], 403);

        $plan = $souscription->plan;

            // dd($plan);
        $pointPlan = [1 => 3,2 => 7,3 => 12];
        $limite = $pointPlan[$plan->id] ?? 0; 

    $existants = EventScanneur::where('event_id', $event->id)->count();
    $reste = $limite - $existants;

    if ($request->nombre > $reste) {
        return response()->json(['error' => "Vous ne pouvez créer plus que $reste scanneur(s)."], 403);
    }

    $created = [];
    $prefix = strtoupper(substr($organisateur->nom, 0, 3)) .
          strtoupper(substr($event->titre, 0, 3));

    // Récupère les utilisateurs existants avec ce préfixe
    $lastUser = Utilisateur::where('role', 'scanneur')
        ->where('nom', 'like', $prefix . '%')
        ->orderByDesc('nom')
        ->first();

    $lastIndex = 0;

    if ($lastUser) {
        $suffix = substr($lastUser->nom, -4); 
        if (is_numeric($suffix)) {
            $lastIndex = intval($suffix) + 1; // recommence à l’indice suivant
        }
    }

    for ($i = 0; $i < $request->nombre; $i++) {
        $username = $prefix . str_pad($lastIndex + $i, 4, '0', STR_PAD_LEFT);
        // $password = Str::random(8);

        $user = Utilisateur::create([
            'nom' => $username,
            'email' => $username . '@scan.local',
            'password' => Hash::make($username),
            'role' => 'scanneur',
            'statut_compte' => 'actif',
        ]);

    EventScanneur::create([
        'event_id' => $event->id,
        'utilisateur_id' => $user->id,
    ]);

    $created[] = [
        'username' => $username,
        'password' => $username,
    ];
    }
    

    return response()->json(['comptes' => $created]);
}
     public function updateScanneurs(Request $request, $scanneurId) { 
        $request->validate([ 
            'password' => 'required|string|min:6',
         ]);

$organisateur = Auth::user();
    $user = Utilisateur::findOrFail($scanneurId);
    
    if($user->role != 'scanneur') return response()->json(['error' => "Vous ne pouvez pas modifier ce utilisateur."], 403);

    $event = $user->eventforScanneur()->first();
    // $limite = $organisateur->abonnement?->scanneur_limit ?? 5;
    $souscription = $organisateur->souscriptionActive();

    if(!$souscription) return response()->json(['error' => "Veuillez réactiver votre souscription"]);

    if($event->utilisateur_id != $organisateur->id) return response()->json(['error' => "Le scanneur indexé n'est pas le votre."], 403);

    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
        'message' => "Mot de passe modifié avec succès.",
        'user' => $user->fresh(),
    ]);
    }

    public function loginScanneur(Request $request)
    {
         $request->validate([
            'nom'=>'required|string',     
            'password'=>'required|string|min:6|',
        ]);
        $scanneur =  Utilisateur::where('role', 'scanneur')
                                ->where('nom', $request->nom)->first();

        // dd($scanneur);
        if (!$scanneur || !Hash::check($request->password, $scanneur->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

    
        Auth::login($scanneur);
        $token = $scanneur->createToken('auth_token')->plainTextToken;
        
    
        return response()->json([
            'access_token' => $token, 
            'token_type' => 'Bearer',
            'message'=> 'Scanneur connecté',
            'role' => $scanneur->role, 
        ]);
    }

    public function listBilletsScanneur()
    {
       $scanneur = Auth::user();

    if ($scanneur->role !== 'scanneur') {
        return response()->json(['error' => 'Accès réservé aux scanneurs.'], 403);
    }

    // je récupère l'événement du scanneur (premier lié)
    $event = $scanneur->eventforScanneur()->first();

    if (!$event) {
        return response()->json(['error' => 'Aucun événement lié à ce scanneur.'], 404);
    }

    
    $billets = Billet::where('event_id', $event->id)->get();

    // Séparation
    $scannes = $billets->where('scanne', true)->values();
    $non_scannes = $billets->where('scanne', false)->values();

    return response()->json([
        'evenement' => $event->titre,
        // 'type_ticket_filtré' => $request->type_ticket ?? 'tous',
        'total_billets' => $billets->count(),
        'total_scannes' => $scannes->count(),
        'total_non_scannes' => $non_scannes->count(),
        'scannes' => $scannes,
        'non_scannes' => $non_scannes,
    ]);
 
    }
        public function scannerBillet($eventId, Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $scanneur = Auth::user();

        if (!$scanneur || $scanneur->role !== 'scanneur') {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé, vous n\'êtes pas authentifé comme scanneur.',
            ], 403);
        }

        // je recupère le billet correspondant à l'event + QR code
        $billet = Billet::with(['utilisateur'])
                        ->where('qr_code', $request->qr_code)
                        ->where('event_id', $eventId)
                        ->first();

        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet invalide ou non trouvé pour cet événement.',
            ], 404);
        }

        if ($billet->status_scan === 'scanné') {
            return response()->json([
                'success' => false,
                'message' => 'Ce billet a déjà été utilisé.',
            ], 400);
        }

        // Marquer le billet comme scanné
        $billet->update([
            'status_scan' => 'scanné',
            'scanned_at' => now(),
            'scanned_by' => $scanneur->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Billet scanné avec succès.',
            'billet' => [
                'id' => $billet->id,
                'utilisateur' => [
                    'nom' => $billet->utilisateur->nom,
                    'email' => $billet->utilisateur->email,
                ],
                'scanned_at' => $billet->scanned_at,
                'scanned_by' => $scanneur->nom,
            ]
        ]);
    }

    public function mesbilletsScannés()
{
    $scanneur = Auth::user();

    if ($scanneur->role !== 'scanneur') {
        return response()->json(['error' => 'Accès refusé.'], 403);
    }

    $event = $scanneur->eventforScanneur()->first();
    if (!$event) {
        return response()->json(['error' => 'Aucun événement lié à ce scanneur.'], 404);
    }

    $billets = Billet::where('event_id', $event->id)
        ->where('scanned_by', $scanneur->id)
        ->orderByDesc('scanned_at')
        ->get();

    return response()->json([
        'evenement' => $event->titre,
        'total' => $billets->count(),
        'billets' => $billets,

    ]);
    }


    public function showScanneur($scanneurId)
    {
        $scanneur = Utilisateur::findOrFail($scanneurId);
        $event = $scanneur->eventforScanneur()->first();
        $billets_scannés = Billet::where('scanned_by', $scanneurId)
                                 ->orderByDesc('scanned_at')->get();
        return response()->json([
            'scanneur' => $scanneur,
            'event' => $event,
            'billets_scannés' => $billets_scannés,
            'nombre_scannés' => $billets_scannés->count(),
        ]);
    }

    public function indexScanneurs($eventId)
    {
        $organisateur = Auth::user();
        $event = Event::findOrFail($eventId);
        
        if($event->utilisateur_id !== $organisateur->id){
            return response()->json(['error' => 'Accès refusé, vous n\'êtes pas l\'organisateur de cet evennement.'], 403);
        }

        $scanneurs = $event->scanneurs()->get();
        return response()->json([
            'scanneurs' => $scanneurs,
        ]);
    }

    public function deleteScanneur($scanneurId)
{
    $organisateur = Auth::user();
    $scanneur = Utilisateur::findOrFail($scanneurId);
    $scanneur = Utilisateur::where('id', $scanneurId)
        ->where('role', 'scanneur')
        ->first();
    if (!$scanneur) {
        return response()->json(['error' => "Scanneur introuvable."], 404);
    }

    $event = $scanneur->eventforScanneur()->first();

    // $event = Event::findOrFail($eventId);

    // Vérifie que l'organisateur est bien propriétaire de l'événement
    if ($event->utilisateur_id !== $organisateur->id) {
        return response()->json(['error' => "Accès refusé, vous n\'êtes pas l\'organisateur de ce scanneur."], 403);
    }

    

    

    // Supprime la liaison scanneur <=> événement
    // EventScanneur::where('event_id', $eventId)
    //     ->where('utilisateur_id', $scanneurId)
    //     ->delete();

    // Supprime l'utilisateur scanneur lui-même
    $scanneur->delete();

    return response()->json(['message' => "Scanneur supprimé avec succès."]);
    }
    public function indexorganisateurScanneurs()
    {
    $organisateur = Auth::user();

    $events = Event::where('utilisateur_id', $organisateur->id)
                   ->with(['scanneurs'])
                   ->get();

    $result = $events->map(function ($event) {
        return [
            'event_id' => $event->id,
            'titre' => $event->titre,
            'scanneurs' => $event->scanneurs->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->nom,
                    'email' => $user->email,
                ];
            }),
        ];
    });

    return response()->json($result);
    }


}
