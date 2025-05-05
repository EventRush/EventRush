<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use App\Models\Souscription;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

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

    public function showUser(Request $request, $id)
    {
        

        $user = Utilisateur::find($id);

        return response()->json([
            'message' => 'Informations de l\'utilisateur',
            'user' => $user,
            'statut organisateur' => $user->organisateurProfil(),
        
        ]);
    }

    public function destroyUser($id)
    {
        $user = Utilisateur::find($id);

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }

    public function showEvent($id)
    {

        $event = Event::findOrFail($id);

        return new EventResource($event->load('organisateur'));
        // return new EventResource($event);

    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
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

}
