<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Notifications\EventFavoriNot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    //

     // Ajouter un événement en favori
     public function store($eventId)
     {
         $user = Auth::user();
         $event = Event::findOrFail($eventId);
 
         if (!$user->favoris->contains($eventId)) {
             $user->favoris()->attach($eventId);
 
             // Envoyer notification
             $user->notify(new EventFavoriNot($event));
 
             return response()->json(['message' => 'Événement ajouté aux favoris avec succès.']);
         }
 
         return response()->json(['message' => 'Événement déjà dans vos favoris.'], 409);
     }

      // Retirer un événement des favoris
    public function destroy($eventId)
    {
        $user = Auth::user();

        if ($user->favoris->contains($eventId)) {
            $user->favoris()->detach($eventId);
            return response()->json(['message' => 'Événement retiré des favoris avec succès.']);
        }

        return response()->json(['message' => 'Événement non trouvé dans vos favoris.'], 404);
    }
    // Liste des événements favoris de l'utilisateur connecté
    public function index()
    {
        $user = Auth::user();
        $favoris = $user->favoris()->with('favorisePar')->get();
        return response()->json($favoris);
    }

 
}
