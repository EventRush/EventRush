<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use App\Models\Souscription;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    /**
     * Summary of indexUsers
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function indexUsers(){
        $users = Utilisateur::class;
        return response()->json([
            'message' => 'Voici les utilisateurs ',
            'utilisateurs' => $users
        ],
    200);
    }

    public function showUser(Request $request, $userId)
    {
        

        $user = Utilisateur::find($userId);

        return response()->json([
            'message' => 'Informations de l\'utilisateur',
            'user' => $user,
            'statut organisateur' => $user->organisateurProfil(),
        
        ]);
    }

    public function destroyUser($userId)
    {
        $user = Utilisateur::find($userId);

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }

    public function showEvent($eventId)
    {

        $event = Event::findOrFail($eventId);

        return new EventResource($event->load('organisateur'));
        // return new EventResource($event);

    }

    public function deleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->delete();
        $event->photos()->delete();
        return response()->json(['message' => 'Événement supprimé avec succès.']);
    }

    public function allSouscriptions()
    {
        $souscriptions = Souscription::class;
        
        return response()->json([
            'message' => 'Liste des souscriptions.',
            'souscriptions' => $souscriptions
        ]);
    }

    public function souscris()
    {
        $souscriptions = Souscription::where('statut', 'actif');
        
        return response()->json([
            'message' => 'Liste des souscriptions.',
            'souscriptions' => $souscriptions
        ]);
    }

    public function usersupdate(Request $request, $userId)
    {
        
        $utilisateur = Utilisateur::findOrFail($userId);
        $request->validate([
            'nom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:utilisateurs,email,' . $utilisateur->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]); 


        // if(!$utilisateur){
        //     return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        // }

    // dd($request->all());

    if ($request->has('nom'))  $utilisateur->nom = $request->nom;
    if ($request->has('email'))    $utilisateur->email = $request->email;
    if ($request->filled('password'))    $utilisateur->password = Hash::make($request->password);
        
    $utilisateur->save();

    // dd($utilisateur);
    return response()->json(['message' => 'Utilisateur mis à jour avec succès.',
                                    'user' => $utilisateur->fresh()], 200);
    }

    public function organisateurScanneurs($orgaId)
    {
    $organisateur = Utilisateur::findOrFail($orgaId);

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
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
        ];
    });

    return response()->json($result);
    }

}
