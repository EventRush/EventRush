<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventVue;
use App\Models\PointLog;
use App\Models\Utilisateur;

class PointService
{
    public static function enregistrerVueEvenement(Utilisateur $utilisateur, Event $event)
    {
        if (EventVue::where('utilisateur_id', $utilisateur->id)->where('event_id', $event->id)->exists()) {
            return;
        }

        EventVue::create([
            'utilisateur_id' => $utilisateur->id,
            'event_id' => $event->id,
        ]);

        $event->increment('points', 1);

        PointLog::firstOrCreate([
            'utilisateur_id' => $utilisateur->id,
            'event_id' => $event->id,
            'type' => 'vue_evenement'
        ], [
            'points' => 1,
        ]);
    }
}