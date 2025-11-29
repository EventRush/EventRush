<?php

namespace App\Services;

use App\Models\EventVue;
use Illuminate\Http\Request;

class EventShowService
{
    /**
     * Enregistre une vue unique basée sur l'adresse IP et l'utilisateur connecté éventuel.
     */
    public function addView(int $eventId, Request $request): void
    {
        $ip = $request->ip();
        $userId = auth()->check() ? auth()->id() : null;

        // Vérifier si cette IP ou cet utilisateur a déjà vu l’event
        $dejaVu = EventVue::where('event_id', $eventId)
            ->where(function ($q) use ($ip, $userId) {
                $q->where('adresse_ip', $ip);

                // if ($userId) {
                //     $q->orWhere('utilisateur_id', $userId);
                // }
            })
            ->exists();

        if (!$dejaVu) {
            EventVue::create([
                'event_id' => $eventId,
                'utilisateur_id' => $userId,
                'adresse_ip' => $ip,
            ]);
        }
    }
}
