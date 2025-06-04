<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\PlansSouscription;
use App\Models\PointLog;
use App\Models\Utilisateur;
use App\Notifications\SuiveurEventCreateNot;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        //
        $orga = $event->utilisateur;
        $souscription = $orga->souscriptionActive();

        if(!$souscription) return;

        $plan = $souscription->plan;

        // $pt = 0;
        // if($plan->id == 1){
        //     $pt = 3;
        // }
        // if($plan->id == 2){
        //     $pt = 7;
        // }
        // if($plan->id == 3){
        //     $pt = 12;
        // }

        $pointPlan = [1 => 3,2 => 7,3 => 12];
        $pt = $pointPlan[$plan->id] ?? 0;  

        
        $pts = $pt + intdiv($orga->points, 10) ;

        PointLog::firstOrCreate([
            'utilisateur_id' => $orga->id,
            'event_id' => $event->id,
            'type' => 'creation'
        ], [
            'points' => $pts,
        ]);

        $event->points = $pts ;
        $event->save();

        foreach ($orga->suiveurs as $suiveur) {
            $suiveur->notify(new SuiveurEventCreateNot($orga, $event));
        }

    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
