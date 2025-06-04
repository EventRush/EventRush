<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // DB::statement("SELECT setval(pg_get_serial_sequence('events','id'), (SELECT MAX(id) FROM events))");
        $faker = Faker::create();
        $user = Utilisateur::where('role', 'organisateur')->get();

        for ($i = 0; $i < 20; $i++) {
            $quantity = $faker->numberBetween(50, 200);
            Event::create([
                'titre' => $faker->sentence(3),
                'utilisateur_id' => $user->random()->id,
                'description' => $faker->paragraph(5),
                'date_debut' => $faker->dateTimeBetween('now', '+1 months'),
                'date_fin' => $faker->dateTimeBetween('+2 months', '+6 months'),
                'lieu' => $faker->city,
                'statut' => $faker->randomElement(['brouillon', 'publié', 'annulé']),
                'affiche' => $faker->imageUrl(640, 480, 'event', true), // Faux lien d'image
                'points' => $faker->numberBetween(20, 100),
            ]);
        }
    }
}
