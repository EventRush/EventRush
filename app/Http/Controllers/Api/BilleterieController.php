<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use FedaPay\Customer;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BilleterieController extends Controller
{
    //
//     public function achat(Request $request)
// {
//     $request->validate([
//         'ticket_id' => 'required|exists:tickets,id',
//         'event_id' => 'required|exists:events,id',
//         'montant' => 'required|numeric',
//         'methode' => 'required|in:carte,PayPal,mobile_money',
//     ]);
    

//     $billet = Billet::create([
//         'event_id' => $request->event_id,
//         'ticket_id' => $request->ticket_id,
//         'utilisateur_id' => Auth::id(), // $request->user()->id
//         'methode' => $request->methode,
//         'status' => 'en_attente',
//         'montant' => $request->montant,
//         'qr_code' => Str::uuid()->toString(),
//         'reference' => strtoupper(Str::random(10)), // REF2024XYZ
//     ]);

//     return response()->json([
//         'message' => 'Billet enregistré. Paiement en attente.',
//         'billet' => $billet
//     ]);
// }
// public function payer(Request $request)
// {
//     $request->validate([
//         'ticket_id' => 'required',
//         // 'event_id' => 'required|exists:events,id',
//         'event_id' => 'required',
//         'montant' => 'required|numeric',
//         'nom' => 'required|string',
//         'prenom' => 'required|string',
//         'telephone' => 'required|numeric',

//         // 'telephone' => 'required|regex:/^(\+229)?(01|02)[0-9]{8}$/',

//     ]);
    
//     // dd($request);
//     $utilisateur = Auth::user(); // ou $request->user()

//     // Étape 1 : créer un billet provisoire
//     $billet = Billet::create([
//         'event_id' => $request->event_id,
//         'utilisateur_id' => $utilisateur->id,
//         'ticket_id' => $request->ticket_id,
//         'montant' => $request->montant,
//         'status' => 'en_attente',
//         'methode' => 'mobile_money',
//         'reference' => Str::uuid(),
//         'qr_code' => null, // À remplir après paiement réussi
//     ]);

//     // Étape 2 : Initialisation FedaPay
//     FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
//     FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));

//     // Étape 3 : Créer la transaction FedaPay
//     $transaction = Customer::create([
//         'description' => 'Achat billet pour événement',
//         'amount' => $billet->montant,
//         'currency' => ['iso' => 'XOF'],
//         'callback_url' => route('paiement.callback', ['reference' => $billet->reference]),
//         'customer' => [
//             'firstname' => $request->nom,
//             'lastname' => $request->prenom,
//             'email' => $utilisateur->email,
//             'phone_number' => [
//                 'number' => $request->telephone,
//                 'country' => 'bj',
//             ]
//         ]
//     ]);

//     // $url = $transaction->generateHostedPaymentUrl();
//      $url = 'https://api.fedapay.com/v1/customers';

//     // Étape 4 : Retourner le lien au frontend
//     return response()->json([
//         'message' => 'Redirection vers le paiement',
//         'payment_url' => $url,
//         'billet_id' => $billet->id,
//     ]);
// }
public function payer(Request $request)
{
    // 1. Validation des données
    $request->validate([
        'ticket_id' => 'required',
        'event_id' => 'required',
        'montant' => 'required|numeric',
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'telephone' => 'required|numeric',
    ]);

    $utilisateur = Auth::user();

    // 2. Création du billet en attente
    $billet = Billet::create([
        'event_id' => $request->event_id,
        'utilisateur_id' => $utilisateur->id,
        'ticket_id' => $request->ticket_id,
        'montant' => $request->montant,
        'status' => 'en_attente',
        'methode' => 'mobile_money',
        'reference' => Str::uuid(),
        'qr_code' => null,
    ]);

    // 3. Configuration FedaPay
    FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
    FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox')); // ou 'live'

    
    // 4. Création de la transaction
    $transaction = Transaction::create([
        'description' => 'Achat billet pour événement',
        'amount' => $billet->montant,
        'currency' => ['iso' => 'XOF'],
        'callback_url' => route('paiement.callback', ['reference' => $billet->reference]),
        'customer' => [
            'firstname' => $request->nom,
            'lastname' => $request->prenom,
            'email' => $utilisateur->email,
            'phone_number' => [
                'number' => $request->telephone,
                'country' => 'BJ',
            ]
        ]
    ]);

    // 5. Génération du lien de paiement
    $token = $transaction->generateToken();

    // 6. Réponse API avec l’URL de paiement
    return response()->json([
        'message' => 'Lien de paiement généré avec succès',
        'payment_url' => $token->url,
        'billet_id' => $billet->id,
    ]);
}

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
        return response()->json(['message' => 'Paiement non validé.', 'status' => $fedapayTransaction->status], 402);
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

}
