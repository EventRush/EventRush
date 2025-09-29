<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tickets = Ticket::where('image', 'not like', 'https://res.cloud%')->get();
        $count = 0;
        foreach ($tickets as $ticket){
            // if(!$ticket->image) {
            // $event = Event::find($id);
            $ticket->image = $ticket->event->affiche; // 'https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg';
            $nomEvent = $ticket->event->titre;
            $ticket->save(); 
            $count++;
            echo "✅ Event $nomEvent, Ticket $ticket->type mis à jour avec son image.\n";
            echo "✅ $count tickets mis à jour avec une image.\n";
            // }

        }
        echo "Fin, $count tickets mis à jour avec une image.\n"; 



    }


       
}
