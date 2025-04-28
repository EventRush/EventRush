<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentairesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $events = Event::all();
        $user = Utilisateur::where('role', '!=', 'admin')->get();

        foreach ($events as $event) {
            for ($i = 0; $i < 15; $i++) { // 15 commentaires par événement
                Commentaire::create([
                    'event_id' => $event->id,
                    'utilisateur_id' => $user->random()->id,
                    'contenu' => $faker->sentence(6),
                    'note' => $faker->numberBetween(1, 5),
                ]);
            }
        }
    }
}
