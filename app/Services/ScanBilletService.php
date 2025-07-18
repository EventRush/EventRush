<?php

namespace App\Services;

use App\Models\Billet;
use App\Models\BilletScanEnAttente;
use App\Models\ScanValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ScanBilletService
{
    public function initierScan($eventId, string $qr_code)
    {
        $scanneur = Auth::user();
        if (!$scanneur || $scanneur->role !== 'scanneur') {
            return ['success' => false, 'message' => 'Non autorisé, vous n\'etes pas scanneur.'];
        }

        $billet = Billet::with('utilisateur')
            ->where('qr_code', $qr_code)
            ->where('event_id', $eventId)
            ->first();

        if (!$billet) {
            return ['success' => false, 'message' => 'Billet non trouvé.'];
        }

        if ($billet->status_scan === 'scanné') {
            return ['success' => false, 'message' => 'Billet déjà utilisé.'];
        }

        $token = Str::uuid();

        ScanValidation::create([
            'billet_id' => $billet->id,
            'scanneur_id' => $scanneur->id,
            'initiated_at' => now(),
            'token' => $token,
        ]);

        return [
            'success' => true,
            'message' => 'Scan en attente de confirmation.',
            'scan_token' => $token,
            'utilisateur' => [
                'nom' => $billet->utilisateur->nom,
                'email' => $billet->utilisateur->email,
            ],
        ];
    }

    public function validerScan(string $token, string $action)
    {
        $scan = ScanValidation::where('token', $token)
                              ->whereNot('status', 'en_attente')->first();

        if (!$scan) {
            return ['success' => false, 'message' => 'Scan introuvable ou déjà traité.'];
        }

        if ($action === 'valider') {
            $scan->billet->update([
                'status_scan' => 'scanné',
                'scanned_at' => now(),
                'scanned_by' => $scan->scanneur_id,
            ]);
            $scan->update(['status' => 'validé']);
            return ['success' => true, 'message' => 'Billet validé avec succès.'];
        }

        if ($action === 'rejeter') {
            $scan->update(['status' => 'rejeté']);
            return ['success' => true, 'message' => 'Scan rejeté.'];
        }

        return ['success' => false, 'message' => 'Action non reconnue.'];
    }
}
