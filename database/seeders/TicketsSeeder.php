<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $faker = Faker::create();
        $events = Event::where('id', '>', 42)->get();

        foreach ($events as $event) {
            foreach (['standart', 'vip1', 'vip2'] as $type) {
                $quantity = $faker->numberBetween(50, 200);
                Ticket::create([
                    'event_id' => $event->id,
                    'type' => $type,
                    'prix' => $faker->randomFloat(2, 1000, 10000),
                    'image' => $faker->imageUrl(640, 480, 'tickets', true),
                    'quantite_restante' => $quantity,
                    'quantité_disponible' => $quantity,
                ]);
            }
        }

    }
}
