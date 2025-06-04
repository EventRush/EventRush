<?php

namespace App\Observers;

use App\Models\PointLog;
use App\Models\Souscription;

class SouscriptionObserver
{
    /**
     * Handle the Souscription "created" event.
     */
    public function created(Souscription $souscription): void
    {
        //
        $utilisateur = $souscription->utilisateur;
        $plan = $souscription->plan;
        $pt = 0;

        if($plan->id == 1){
            $pt = 0;
        }
        if($plan->id == 2){
            $pt = 3;
        }
        if($plan->id == 3){
            $pt = 5;
        }

        // Exemple : 10 points à chaque souscription
        PointLog::create([
            'utilisateur_id' => $utilisateur->id,
            'type' => 'souscription_à_plan' . $plan->nom,
            'points' => 10+$pt,
        ]);

        $utilisateur->points += 10+$pt;
        $utilisateur->save();

    }

    /**
     * Handle the Souscription "updated" event.
     */
    public function updated(Souscription $souscription): void
    {
        //
    }

    /**
     * Handle the Souscription "deleted" event.
     */
    public function deleted(Souscription $souscription): void
    {
        //
    }

    /**
     * Handle the Souscription "restored" event.
     */
    public function restored(Souscription $souscription): void
    {
        //
    }

    /**
     * Handle the Souscription "force deleted" event.
     */
    public function forceDeleted(Souscription $souscription): void
    {
        //
    }
}
