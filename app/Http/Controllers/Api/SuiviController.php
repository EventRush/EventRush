<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganisateurProfile;
use App\Models\Suivi;
use App\Notifications\OrganisateurSuiviNot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    //

    // Liste des organisateurs suivis
    public function index()
    {
        $user = Auth::user();

        $organisateursSuivis = $user->organisateursSuivis()->with('suiveurs')->get();

        return response()->json([
            'organisateurs_suivis' => $organisateursSuivis
        ]);
    }


    public function suivre($organisateurId)
    {
        $user = Auth::user();

        // Vérifier si déjà suivi
        $existe = Suivi::where('utilisateur_id', $user->id)
                       ->where('organisateur_id', $organisateurId)
                       ->exists();

        if ($existe) {
            return response()->json(['message' => 'Déjà suivi.']);
        }

        Suivi::create([
            'utilisateur_id' => $user->id,
            'organisateur_id' => $organisateurId,
        ]);
        // Envoyer notification
    $organisateur = OrganisateurProfile::findOrFail($organisateurId);
    $organisateur->utilisateur->notify(new OrganisateurSuiviNot($user));



        return response()->json(['message' => 'Organisateur suivi avec succès.']);
    }

    public function nePlusSuivre($organisateurId)
    {
        $user = Auth::user();

        Suivi::where('utilisateur_id', $user->id)
             ->where('organisateur_id', $organisateurId)
             ->delete();

        return response()->json(['message' => 'Organisateur désuivi avec succès.']);
    }


}
