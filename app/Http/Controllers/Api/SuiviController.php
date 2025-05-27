<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganisateurProfile;
use App\Models\Suivi;
use App\Models\Utilisateur;
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

    // Récupérer les utilisateurs suivis avec leurs suiveurs
    $utilisateursSuivis = $user->utilisateurSuivis()->with('suiveurs')->get();

    return response()->json([
        'utilisateurs_suivis' => $utilisateursSuivis
    ]);
}

public function suivre($userId)
{
    $user = Auth::user();

    // Ne pas se suivre soi-même
    if ($user->id == $userId) {
        return response()->json(['message' => 'Vous ne pouvez pas vous suivre vous-même.'], 400);
    }

    // Vérifier si déjà suivi
    $existe = Suivi::where('utilisateur_id', $user->id)
                   ->where('suivi_id', $userId)
                   ->exists();

    if ($existe) {
        return response()->json(['message' => 'Déjà suivi.']);
    }

    Suivi::create([
        'utilisateur_id' => $user->id,
        'suivi_id' => $userId,
    ]);

    // Notification
    $suivi = Utilisateur::findOrFail($userId);
    $suivi->notify(new OrganisateurSuiviNot($user));

    return response()->json(['message' => 'Utilisateur suivi avec succès.']);
}

public function nePlusSuivre($userId)
{
    $user = Auth::user();

    Suivi::where('utilisateur_id', $user->id)
         ->where('suivi_id', $userId)
         ->delete();

    return response()->json(['message' => 'Utilisateur désuivi avec succès.']);
}


   



}
