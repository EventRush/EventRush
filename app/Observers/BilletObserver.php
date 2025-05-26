<?php

namespace App\Observers;

use App\Models\Billet;
use App\Models\PointLog;

class BilletObserver
{
    /**
     * Handle the Billet "created" event.
     */
    public function created(Billet $billet): void
    {
        //
        $utilisateur = $billet->utilisateur;

        $ticket = $billet->ticket;

        if($ticket->type == 'standart'){
            $pt = 0;
        }
        if($ticket->type == 'vip1'){
            $pt = 3;
        }
        if($ticket->type == 'vip2'){
            $pt = 5;
        }



        // Exemple : 10 points à chaque souscription
        PointLog::create([
            'utilisateur_id' => $utilisateur->id,
            'type' => 'achat_billet ' . $billet->id,
            'points' => 8+$pt,
        ]);

        $utilisateur->points += 8+$pt;
        $utilisateur->save();
    }

    /**
     * Handle the Billet "updated" event.
     */
    public function updated(Billet $billet): void
    {
        //
    }

    /**
     * Handle the Billet "deleted" event.
     */
    public function deleted(Billet $billet): void
    {
        //
    }

    /**
     * Handle the Billet "restored" event.
     */
    public function restored(Billet $billet): void
    {
        //
    }

    /**
     * Handle the Billet "force deleted" event.
     */
    public function forceDeleted(Billet $billet): void
    {
        //
    }
}
