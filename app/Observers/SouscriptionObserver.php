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

        // Exemple : 10 points à chaque souscription
        PointLog::create([
            'utilisateur_id' => $utilisateur->id,
            'type' => 'souscription',
            'points' => 10,
        ]);

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
