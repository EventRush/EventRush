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
        $events = Event::all();

        foreach ($events as $event) {
            for ($i = 0; $i < 3; $i++) { // 3 tickets par événement
                Ticket::create([
                    'event_id' => $event->id,
                    'type' => $faker->randomElement(['standart', 'vip1', 'vip2']),
                    'prix' => $faker->randomFloat(2, 1000, 10000),
                    'image' => $faker->imageUrl(640, 480, 'tickets', true),
                    'quantité_disponible' => $faker->numberBetween(50, 200),
                ]);
            }
        }
    }
}
