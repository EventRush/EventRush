<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Event;
use App\Models\Ticket;
use FedaPay\Customer;
use FedaPay\FedaPay;
use FedaPay\Transaction;
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

    // 2. Création du billet en attente
    // $billet = Billet::create([
    //     'event_id' => $request->event_id,
    //     'utilisateur_id' => $utilisateur->id,
    //     'ticket_id' => $request->ticket_id,
    //     'montant' => $request->montant,
    //     'status' => 'en_attente',
    //     'methode' => 'mobile_money',
    //     'reference' => Str::uuid(),
    //     'qr_code' => null,
    // ]);
    // $evenement = Event::find( $request->event_id);

    
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
        // 'callback_url' => 'https://8e2b-2c0f-2a80-38f-2610-e97d-9081-f6ba-14e5.ngrok-free.app/api/paiement/callback?reference=' . $billet->reference,
        'customer' => [
            'firstname' => $request->prenom ?: 'Inconnu',
            'lastname' => $request->nom ?: $utilisateur->nom,
            'email' => $utilisateur->email,
            'phone_number' => [
                'number' => $request->telephone ?: 64000001,
                'country' => 'BJ',
            ],
            "metadata" => [
                "user_id" => $utilisateur->id,
                "ticket" => $ticket->id,
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

public function webhookBillet(Request $request)
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
