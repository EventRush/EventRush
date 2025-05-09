<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganisateurProfile;
use App\Models\PlansSouscription;
use App\Models\Souscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Support\Facades\Log;

class SouscriptionController extends Controller
{
    // public function paiementsouscrire(Request $request){
    //     $request->validate([
    //         'plans_souscription_id' => 'required|exists:plans_souscriptions,id',
    //         'telephone' => 'nullable|string',
    //         'nom' => 'nullable|string', 
    //     ]);
    
    //     $plan = PlansSouscription::find($request->plans_souscription_id);
    //     if (!$plan) {
    //         return response()->json([
    //             'message' => 'Plans introuvable.'
    //         ], 404);
    //     }
    //     $utilisateur = $request->user();
    
    //     try {
    //         FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
    //         FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));

    //         $transaction = Transaction::create([
    //         //    dd([ 
    //             "description" => "Souscription organisateur - {$utilisateur->nom} -  {$plan->nom}",
    //             'amount' => $plan->prix,
    //             "currency" => ["iso" => "XOF"],
    //             'callback_url' => 'https://8e2b-2c0f-2a80-38f-2610-e97d-9081-f6ba-14e5.ngrok-free.app/api/paiement/callback?reference=',
    //             "customer" => [
    //                 "firstname" => $utilisateur->prenom ?: 'Inconnu',
    //                 "lastname" => $utilisateur->nom,
    //                 "email" => $utilisateur->email,
    //                 "phone" => [
    //                     "number" => $request->telephone,
    //                     "country" => 'BJ'
    //                 ]
    //             ]
    //             // ])
    //         ]);
    
    //         $token = $transaction->generateToken();
    
    //         //expirer l'ancienne souscription active si elle existe
    //     $ancienne = $utilisateur->souscriptionActive();
    //     if($ancienne) {
    //         $ancienne->statut = 'expiré';
    //         $ancienne->save();
    //     }

    //         // Enregistrer la transaction côté base (optionnel mais recommandé)
    //         // dd([ 
    //         $utilisateur->souscription()->create([
    //             'montant' => $plan->prix,
    //             'statut_paiement' => 'en_attente',
    //             'reference' => $transaction->id,
    //             'methode' => 'mobile_money'
    //             // ])
    //         ]);
    
    //         return response()->json([
    //             'message' => 'Paiement en attente de validation.',
    //             'payment_url' => $token->url
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Erreur lors de la création du paiement',
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
            
    //     }
    // }

    
    public function paiementsouscrire(Request $request)
{
    $request->validate([
        'plans_souscription_id' => 'required|exists:plans_souscriptions,id',
        'telephone' => 'nullable|numeric',
    ]);

    $plan = PlansSouscription::findOrFail($request->plans_souscription_id);
    $utilisateur = $request->user();

    try {
        FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
        FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));

        $reference = uniqid(); // pour suivre la transaction plus facilement

        $transaction = Transaction::create([
            // dd([

          
            "description" => "Souscription organisateur - {$utilisateur->nom} - {$plan->nom}",
            'amount' => (int) $plan->prix,
            // "amount" => 5000,
            "currency" => ["iso" => "XOF"],
            "callback_url" => env('FEDAPAY_CALLBACK_URL') . '?reference=' . $reference,
            "customer" => [
                "firstname" => $request->prenom ?: 'Inconnu',
                "lastname" => $request->nom ?: $utilisateur->nom,
                "email" => $utilisateur->email,
                "phone" => [
                    "number" => $request->telephone ?: 64000001,
                    "country" => 'BJ'
                ]
            ],
            "metadata" => [
                "type" => "Souscription",
                "user_id" => $utilisateur->id,
                "plan_id" => $plan->id,
                "reference" => $reference
            ]
            // ])
        ]);

        return response()->json([
            'url' => $transaction->generateToken()->url,
            'reference' => $reference
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la création de la transaction',
            'error' => $e->getMessage()
        ], 500);
    }
}


//     public function webhooksouscription(Request $request)

// {
//     $payload = $request->all();

//     if ($payload['event'] === 'transaction.approved') {
//         $data = $payload['data']['object'];
//         $ref = $data['id'];
//         $email = $data['customer']['email'];
//         $montant = $data['amount'] / 100; 

//         // Trouver l'utilisateur
//         $utilisateur = Utilisateur::where('email', $email)->first();
//         if (!$utilisateur) return response()->json(['message' => 'Utilisateur non trouvé'], 404);

//         // Trouver la souscription en attente
//         $souscription = Souscription::where('reference', $ref)
//             ->where('statut_paiement', 'en_attente')
//             ->first();

//         if (!$souscription) return response()->json(['message' => 'Souscription introuvable'], 404);

//         // Activer la souscription
//         $souscription->update([
//             'statut' => 'actif',
//             'statut_paiement' => 'success',
//             'date_debut' => now(),
//             'date_fin' => now()->addDays($souscription->plan->duree_jours),
//             'montant' => $montant,
//         ]);

//         // Donner le rôle
//         // vérification du role 'organisateur'
//         if($utilisateur->role !== 'organisateur'){
//             $utilisateur->role = 'organisateur';
//             $utilisateur->save();
//         }
//         // Créer le profil organisateur si pas encore fait
//         if (!$utilisateur->organisateurProfile) {
//             OrganisateurProfile::create(['utilisateur_id' => $utilisateur->id]);
//         }

//         return response()->json([
//             'message' => 'Souscription activée',
//             'souscription' => $souscription
//         ]);
//     }

//     return response()->json(['message' => 'Événement ignoré'], 200);
// }

    // public function souscriptionWebhook(Request $request)
    // {
    //     $payload = $request->all();

    //     if (!isset($payload['event']) || $payload['event'] !== 'transaction.approved') {
    //         return response()->json(['message' => 'Événement non géré'], 400);
    //     }

    //     $transaction = $payload['data']['object'];
    //     $metadata = $transaction['metadata'];

    //     $user = Utilisateur::find($metadata['user_id']);
    //     $plan = PlansSouscription::find($metadata['plan_id']);
    //     $reference = $metadata['reference'];

    //     if (!$user || !$plan) {
    //         return response()->json(['message' => 'Utilisateur ou plan introuvable'], 404);
    //     }

    //     // Donner le rôle
    //     // vérification du role 'organisateur'
    //     if($user->role !== 'organisateur'){
    //         $user->role = 'organisateur';
    //         $user->save();
    //     }

    //     // Créer l’organisateur si nécessaire
    //     $organisateur = $user->organisateurProfile;
    //     if (!$organisateur) {
    //         $organisateur = OrganisateurProfile::create([
    //             'utilisateur_id' => $user->id,
    //         ]);
    //     }

    //     // Enregistrer la souscription
    //     $souscription = Souscription::create([
    //         'organisateur_id' => $organisateur->id,
    //         'utilisateur_id' => $user->id,
    //         'plans_souscription_id' => $plan->id,
    //         'date_debut' => now(),
    //         'date_fin' => now()->addDays($plan->duree),
    //         'methode' => 'mobile_money',
    //         'statut' => 'actif',
    //         'statut_paiement' => 'success',
    //         'montant' => $plan->prix,
    //         'reference' => $reference,

    //     ]);

    //     return response()->json([
    //         'message' => 'Souscription enregistrée',
    //         'souscription' => $souscription
    //     ], 200);
    // }
    //

    public function souscriptionWebhook(Request $request)
{
    $payload = $request->all();
    // dd($payload);
    // Vérification de l'événement
    if ($payload['status'] == 'approved' && $payload['name'] == 'transaction.approved') {
        // Log::error("Événement non géré : " . $payload['event']);
        return response()->json(['message' => 'Événement non géré'], 400);
    }
    if (!isset($payload['event']) || ($payload['event'] !== 'approved' && $payload['name'] !== 'transaction.approved')) {
        // Log::error("Événement non géré : " . $payload['event']);
        return response()->json(['message' => 'Événement non '], 400);
    }

    $transaction = $payload['data']['object'];
    $metadata = $transaction['metadata'];

    // Vérification des métadonnées
    if (!isset($metadata['user_id'], $metadata['plan_id'], $metadata['reference'])) {
        // Log::error("Métadonnées manquantes dans le webhook : " . json_encode($metadata));
        return response()->json(['message' => 'Métadonnées manquantes'], 400);
    }

    $user = Utilisateur::find($metadata['user_id']);
    $plan = PlansSouscription::find($metadata['plan_id']);
    $reference = $metadata['reference'];

    if (!$user || !$plan) {
        // Log::error("Utilisateur ou plan introuvable : User ID = {$metadata['user_id']}, Plan ID = {$metadata['plan_id']}");
        return response()->json(['message' => 'Utilisateur ou plan introuvable'], 404);
    }

    // Donner le rôle d'organisateur si ce n'est pas déjà fait
    if ($user->role !== 'organisateur') {
        $user->role = 'organisateur';
        $user->save();
    }

    // Créer l’organisateur si nécessaire
    $organisateur = $user->organisateurProfile;
    if (!$organisateur) {
        $organisateur = OrganisateurProfile::create([
            'utilisateur_id' => $user->id,
        ]);
    }

    // Vérification pour éviter de dupliquer l'enregistrement
    $existingSouscription = Souscription::where('reference', $reference)->first();
    if (!$existingSouscription) {
        // Enregistrer la souscription
        $souscription = Souscription::create([
            'organisateur_id' => $organisateur->id,
            'utilisateur_id' => $user->id,
            'plans_souscription_id' => $plan->id,
            'date_debut' => now(),
            'date_fin' => now()->addDays($plan->duree),
            'methode' => 'mobile_money',
            'statut' => 'actif',
            'statut_paiement' => 'success',
            'montant' => $plan->prix,
            'reference' => $reference,
        ]);

        // Log::info("Souscription enregistrée avec succès : " . json_encode($souscription));

        return response()->json([
            'message' => 'Souscription enregistrée',
            'souscription' => $souscription
        ], 200);
    } else {
        // Log::warning("Souscription déjà existante pour la référence : " . $reference);
        return response()->json([
            'message' => 'Souscription déjà enregistrée',
            'souscription' => $existingSouscription
        ], 200);
    }
}
    /**
     * Summary of souscrire
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function souscrire(Request $request){
        $request->validate([
           'plans_souscription_id' => 'required|exists:plans_souscriptions,id',
        ]);

        $plan = PlansSouscription::find($request->plans_souscription_id);
        $utilisateur = $request->user();
         
        //expirer l'ancienne souscription active si elle existe
        $ancienne = $utilisateur->souscriptionActive();
        if($ancienne) {
            $ancienne->statut = 'expiré';
            $ancienne->save();
        }

        // creer une nouvelle ligne
        $souscription = new Souscription([
            'plans_souscription_id' => $plan->id,
            'date_debut' => now(),
            'date_fin' => now()->addDays($plan->duree_jours),
            'statut' => 'actif',
        ]);
        $utilisateur->souscription()->save($souscription);

        // vérification du role 'organisateur'
        if($utilisateur->role !== 'organisateur'){
            $utilisateur->role = 'organisateur';
            $utilisateur->save();
        }

        $user = Auth::user(); 
        $orga = OrganisateurProfile::where('utilisateur_id', $user->id)->first();

        
        if(!$orga){
          OrganisateurProfile::create([
            'utilisateur_id' => $user->id
          ]) ;
        }

        return response()->json([
            'message' => 'Souscription éffectué.',
            'souscription' => $souscription
        ]);
    }

    // Voir les plans disponibles
    /**
     * Summary of plans
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function plans()
    {
        $plans = PlansSouscription::all();
        return response()->json($plans);
    }


    public function monAbonnement(Request $request){
        $active = $request->user()->souscriptionActive();

        if (!$active){
            return response()->json([
                'message' => 'Aucune souscription active.'
            ], 404);

        }
        $plan = $active->plan;
        return response()->json([
            'plan' => $plan,
            'souscription' => $active
        ]);

    }

     // Vérifier si la souscription est valide
     /**
      * Summary of statut
      * @param \Illuminate\Http\Request $request
      * @return mixed|\Illuminate\Http\JsonResponse
      */
     public function statut(Request $request)
     {
         $user = Auth::user();
 
         $active = $user->souscription()->where('statut', 'actif')->where('date_fin', '>', now())->first();
 
         return response()->json([
             'active' => !!$active,
             'souscription' => $active
         ]);
     }
 
     // Historique
     /**
      * Summary of historique
      * @return mixed|\Illuminate\Http\JsonResponse
      */
     public function historique()
     {
        // $hist = $request->user()->souscriptionActive()->with('plan')->latest()->get();
        // return response()->json($hist);

         return response()->json(auth()->user()->souscription()->with('plan')->latest()->get());
        }
 

}
