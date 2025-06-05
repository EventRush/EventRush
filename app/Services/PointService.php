<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventVue;
use App\Models\PointLog;
use App\Models\Utilisateur;

class PointService
{
    public static function ajouterVueEvenement(Utilisateur $utilisateur, Event $event)
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

     public static function ajouterFavoriEvenement(Utilisateur $utilisateur, Event $event)
    {

        $event->increment('points', 2);

        PointLog::firstOrCreate([
            'utilisateur_id' => $utilisateur->id,
            'event_id' => $event->id,
            'type' => 'evenement_favorise'
        ], [
            'points' => 2,
        ]);
    }

    public static function ajouterNoteEvenement(Utilisateur $utilisateur,Event $event, $oldnote)
    {   
        
        $event->increment('points', 1);

        PointLog::firstOrCreate([
            'utilisateur_id' => $utilisateur->id,
            'event_id' => $event->id,
            'type' => 'evenement_note'
        ], [
            'points' => 2,
        ]);
    }
}