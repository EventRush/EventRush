<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganisateurProfile;
use App\Models\Souscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SouscriptionController extends Controller
{
    //
    public function souscrire(Request $request){
        $request->validate([
            'type' =>'required|string|in:standard,premium',
            'duree' => 'required|integer|min:15' //jours
        ]);

        $utilisateur = $request->user();
         
        //expirer l'ancienne souscription active si elle existe
        $ancienne = $utilisateur->souscriptionActive();
        if($ancienne) {
            $ancienne->statut = 'expiré';
            $ancienne->save();
        }

        // creer une nouvelle ligne
        $souscription = new Souscription([
            'type' => $request->type,
            'date_debut' => now(),
            'date_fin' => now()->addDays($request->duree),
            'statut' => 'actif',
        ]);
        $utilisateur->souscription()->save($souscription);

        // vérification du role 'organisateur'
        if($utilisateur->role !== 'organisateur'){
            $utilisateur->role = 'organisateur';
            $utilisateur->save();
        }

        $user = Auth::user(); 
        $orga = OrganisateurProfile::where('utilisateur_id', $user->id);

        
        if($orga){
          OrganisateurProfile::create([
            'utilisateur_id' => $user->id
          ]) ;
        }

        return response()->json([
            'message' => 'Souscription éffectué.',
            'souscription' => $souscription
        ]);
    }

    public function monAbonnement(Request $request){
        $active = $request->user()->souscriptionActive();

        if (!$active){
            return response()->json([
                'message' => 'Aucune souscription active.'
            ], 404);

        }
        return response()->json($active);

    }

}
