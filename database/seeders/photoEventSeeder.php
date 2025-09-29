<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class photoEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $events = Event::all();
        $faker = Faker::create();
        $count = 0;
        foreach ($events as $event){
            $tickets = $event->tickets; 
            if($tickets->isEmpty()) { 
                
            // $ticket->image = $event->event->affiche; // 'https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg';
            $nomEvent = $event->titre;
            // $event->save(); 
            foreach (['standart', 'vip1', 'vip2'] as $type) {
                $quantity = $faker->numberBetween(50, 200);
                Ticket::create([
                    'event_id' => $event->id,
                    'type' => $type,
                    'prix' => $faker->randomFloat(2, 1000, 10000),
                    'image' => $event->affiche,
                    'quantite_restante' => $quantity,
                    'quantité_disponible' => $quantity,
                ]);
                echo "✅ Event $nomEvent, ticket $type créés.\n";
            }
            $count++;
            echo "✅ Event $nomEvent, 3 ticket créés.\n";
            echo "✅ $count check .\n";
            }

        }
        echo "Fin, $count check.\n"; 
    }
}
