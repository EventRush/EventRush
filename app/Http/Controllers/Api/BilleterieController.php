<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Utilisateur;
use FedaPay\Customer;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BilleterieController extends Controller
{
/**
 * Summary of payer
 * @param \Illuminate\Http\Request $request
 * @return mixed|\Illuminate\Http\JsonResponse
 */
public function payer(Request $request)
{
    // 1. Validation des données
    $request->validate([
        'ticket_id' => 'required|exists:tickets,id',
        // 'event_id' => 'required',
        // 'montant' => 'required|numeric',
        'nom' => 'nullable|string',
        'prenom' => 'nullable|string',
        'telephone' => 'nullable|numeric',
    ]);

    $utilisateur = Auth::user();
    $ticket = Ticket::findOrFail($request->ticket_id);
    $evenement = Event::find( $ticket->event_id);

    if(!$ticket){
        return response()->json(['message' => 'Ticket non trouvé'], 404);
    }
    if($ticket->quantite_restante<1){
        return response()->json(['message' => 'La totalité des tickets a déjà été vendue'], 403);
    }

    if($ticket->date_limite_vente && now()->greaterThan($ticket->date_limite_vente)){
        return response()->json(['message' => 'La vente de ce ticket est terminée'], 403);
    }


    // 3. Configuration FedaPay
    FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
    FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox')); // ou 'live'

    
    // 4. Création de la transaction
    $reference = uniqid(); // pour suivre la transaction plus facilement


    $transaction = Transaction::create([
        // dd([ 

        'description' => "Achat billet pour - {$evenement->titre} - de - {$utilisateur->nom}" ,
        'amount' => (int) $ticket->prix,
        'currency' => ['iso' => 'XOF'],
        "callback_url" => 'https://gestevent-main-ai7iif.laravel.cloud/api/billet/webhook' . '?reference=' . $reference,
                    // 'callback_url' => 'https://8e2b-2c0f-2a80-38f-2610-e97d-9081-f6ba-14e5.ngrok-free.app/api/paiement/callback?reference=' . $billet->reference,
        'customer' => [
            'firstname' => $request->prenom ?: 'Inconnu',
            'lastname' => $request->nom ?: $utilisateur->nom,
            'email' => $utilisateur->email,
            'phone_number' => [
                'number' => $request->telephone ?: 64000001,
                'country' => 'BJ',
            ],
            "custom_metadata" => [
                'type' => 'Billet',
                "user_id" => $utilisateur->id,
                "ticket_id" => $ticket->id,
                "reference" => $reference
            ]
        ]
        // ])

    ]);

    // 5. Génération du lien de paiement
    $token = $transaction->generateToken();


    // $billet->billet_fedapay_id = $transaction->id;
    // $billet->save();

    // 6. Réponse API avec l’URL de paiement
    return response()->json([
        'message' => 'Lien de paiement généré avec succès',
        'payment_url' => $token->url,
        'reference' => $reference
    ]);
}
    /**
     * Summary of webhookBillet
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function webhookBillet(Request $request)
{
    $endpoint_secret = 'wh_sandbox_k3xfQnlg3C75xcetgkNSJeoR';
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'];
    $event = null;

    try {
    $event = Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
}catch(\UnexpectedValueException $e) {
    // Invalid payload

    http_response_code(400);
    exit();
} catch(\FedaPay\Error\SignatureVerification $e) {
    // Invalid signature

    http_response_code(400);
    exit();
}

    
    if ($event->name === 'transaction.approved' ) {

        $transaction = $event->entity;

    
    $metadata = $transaction->custom_metadata;

    // Vérification des métadonnées
    if (!isset($metadata->user_id, $metadata->ticket_id, $metadata->reference)) {
        // Log::error("Métadonnées manquantes dans le webhook : " . json_encode($metadata));
        return response()->json(['message' => 'Métadonnées manquantes'], 400);
    }

    $user = Utilisateur::find($metadata->user_id);
    $ticket = Ticket::find($metadata->ticket_id);
    // $evenement = Event::find($ticket->event_id);
    $reference = $metadata->reference;

    

    if (!$user || !$ticket) {
        return response()->json(['message' => 'Utilisateur ou ticket introuvable'], 404);
    }

    if (!$reference) {
        return response()->json(['message' => 'Référence manquante.'], 400);
    }

    $billet_paye = Billet::where('reference', $reference);

    if ($billet_paye->status === 'paye') {
        return response()->json(['message' => 'Paiement déjà confirmé.']);
    }

    // Enregistrer la souscription
    $billet = Billet::create([
        'event_id' => $ticket->event_id,
        'utilisateur_id' => $user->id,
        'ticket_id' => $ticket->id,
        'methode' => 'mobile_money',
        'status' => 'paye',
        'montant' => $ticket->prix,
        'qr_code' => Str::uuid(),
        'reference' => $reference,
    ]);
    $ticket->quantite_restante -= 1 ;
    $ticket->save();

    return response()->json([
        'message' => 'Billet acheté',
        'billet' => $billet,
        'qr_code' => $billet->qr_code
    ], 200);
    }

        return response()->json(['message' => 'Événement non géré'], 400);

    
}

// public function webhookBillet(Request $request)
// {
    
//     $payload = $request->all();

//     if (!isset($payload['event']) || $payload['event'] !== 'transaction.paid') {
//         return response()->json(['message' => 'Événement non géré'], 400);
//     }

    

//     $transaction = $payload['data']['object'];
//     $metadata = $transaction['metadata'];

//     $user = Utilisateur::find($metadata['user_id']);
//     $ticket = Ticket::find($metadata['ticket_id']);
//     $reference = $metadata['reference'];

//     if (!$user || !$ticket) {
//         return response()->json(['message' => 'Utilisateur ou ticket introuvable'], 404);
//     }

//     if (!$reference) {
//         return response()->json(['message' => 'Référence manquante.'], 400);
//     }

//     $billet_paye = Billet::where('reference', $reference);

//     if ($billet_paye->status === 'paye') {
//         return response()->json(['message' => 'Paiement déjà confirmé.']);
//     }

//     // Enregistrer la souscription
//     $billet = Billet::create([
//         'event_id' => $ticket->event_id,
//         'utilisateur_id' => $user->id,
//         'ticket_id' => $ticket->id,
//         'methode' => 'mobile_money',
//         'status' => 'paye',
//         'montant' => $ticket->prix,
//         'qr_code' => Str::uuid(),
//         'reference' => $reference,
//     ]);
//     $ticket->quantite_restante -= 1 ;
//     $ticket->save();

//     return response()->json([
//         'message' => 'Billet acheté',
//         'billet' => $billet,
//         'qr_code' => $billet->qr_code
//     ], 200);
// }

/**
 * Summary of callback
 * @param \Illuminate\Http\Request $request
 * @return mixed|\Illuminate\Http\JsonResponse
 */
public function callback(Request $request)
{
    $reference = $request->query('reference');

    if (!$reference) {
        return response()->json(['message' => 'Référence manquante.'], 400);
    }

    $billet = Billet::where('reference', $reference)->first();

    if (!$billet) {
        return response()->json(['message' => 'Billet introuvable.'], 404);
    }

    if ($billet->status === 'paye') {
        return response()->json(['message' => 'Paiement déjà confirmé.']);
    }

    // Vérifier auprès de FedaPay
    FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
    FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));


    $fedapayTransaction = Transaction::retrieve($billet->billet_fedapay_id);

    if ($fedapayTransaction->status !== 'approved') {
        return response()->json(['message' => 'Paiement non valide.', 'status' => $fedapayTransaction->status], 402);
    }

    // Paiement validé, on confirme
    $billet->status = 'paye';
    $billet->qr_code = Str::uuid();
    $billet->save();
    return response()->json([
        'message' => 'Paiement confirmé via FedaPay',
        'qr_code' => $billet->qr_code,
        'billet' => $billet
    ]);
}

    public function verifierBillet(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $billet = Billet::where('qr_code', $request->qr_code)
                        ->with('utilisateur')
                        ->first();

        if (!$billet) {
            return response()->json([
                'success' => false,
                'message' => 'Billet invalide ou non trouvé.'
            ], 404);
        }

        // Vérifier si le billet a déjà été scanné
        if ($billet->status_scan === 'scanné') {
            return response()->json([
                'success' => false,
                'message' => 'Ce billet a déjà été utilisé.'
            ], 400);
        }

        // Marquer le billet comme scanné
        $billet->update([
            'status_scan' => 'scanné',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'billet' => [
                'id' => $billet->id,
                'utilisateur' => [
                    'nom' => $billet->utilisateur->nom,
                    'email' => $billet->utilisateur->email,
                ]
            ]
        ]);
    }


}
