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
     public function generateScanneurs(Request $request) { 
        $request->validate([ 
            'event_id' => 'required|exists:events,id', 
            'nombre' => 'required|integer|min:1' ]);

$organisateur = Auth::user();
    $event = Event::findOrFail($request->event_id);

    // $limite = $organisateur->abonnement?->scanneur_limit ?? 5;
    $souscription = $organisateur->souscriptionActive();

    if(!$souscription) return response()->json(['error' => "Veuillez réactiver votre souscription"]);

    if($event->utilisateur_id != $organisateur->id) return response()->json(['error' => "Vous ne pouvez pas créer de scanneur(s) pour cet événement."], 403);

        $plan = $souscription->plan;


        $pointPlan = [1 => 3,2 => 7,3 => 12];
        $limite = $pointPlan[$plan->id] ?? 0; 

    $existants = EventScanneur::where('event_id', $event->id)->count();
    $reste = $limite - $existants;

    if ($request->nombre > $reste) {
        return response()->json(['error' => "Vous ne pouvez créer plus que $reste scanneur(s)."], 403);
    }

    $created = [];
    $prefix = strtoupper(substr($organisateur->name, 0, 3)) .
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
        $password = Str::random(8);

        $user = Utilisateur::create([
            'name' => $username,
            'email' => $username . '@scan.local',
            'password' => Hash::make($password),
            'role' => 'scanneur',
        ]);

    EventScanneur::create([
        'event_id' => $event->id,
        'utilisateur_id' => $user->id,
    ]);

    $created[] = [
        'username' => $username,
        'password' => $password,
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
            'nom'=>'required|string|nom',     
            'password'=>'required|string|min:6|',
        ]);
        $scanneur =  Utilisateur::where('role', 'scanneur')
                                ->where('nom', $request->nom)->first();
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

    // Filtrage par type_ticket si fourni
    // $query = Billet::where('event_id', $event->id);

    // if ($request->filled('type_ticket')) {
    //     $query->where('type_ticket', $request->type_ticket);
    // }

    // $billets = $query->get();

    
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
    // public function scannerBillet(Request $request)
    // {
    //     $request->validate([
    //     'qr_code' => 'required|string',
    // ]);

    // $scanneur = Auth::user();

    // if ($scanneur->role !== 'scanneur') {
    //     return response()->json(['error' => 'Accès refusé, vous n\'êtes pas scanneur.'], 403);
    // }

    // $event = $scanneur->eventforScanneur()->first();
    // if (!$event) {
    //     return response()->json(['error' => 'Aucun événement lié à ce scanneur.'], 404);
    // }

    // $billet = Billet::where('qr_code', $request->qr_code)
    //     ->where('event_id', $event->id)
    //     ->first();

    // if (!$billet) {
    //     return response()->json(['error' => 'Billet introuvable pour cet événement.'], 404);
    // }

    // if ($billet->isScanned()) {
    //     return response()->json(['message' => 'Ce billet a déjà été scanné.'], 409);
    // }

    // $billet->status_scan = 'scanné';
    // $billet->scanned_by = $scanneur->id;
    // $billet->scanned_at = now();
    // $billet->save();

    // return response()->json([
    //     'message' => 'Billet scanné avec succès.',
    //     'billet' => $billet->fresh(),
    // ]);

    // }
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



}
