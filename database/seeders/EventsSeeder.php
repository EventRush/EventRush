<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $faker = Faker::create();
        $user = Utilisateur::where('role', 'organisateur')->get();

        for ($i = 0; $i < 20; $i++) {
            Event::create([
                'titre' => $faker->sentence(3),
                'utilisateur_id' => $user->random()->id,
                'description' => $faker->paragraph(5),
                'date_debut' => $faker->dateTimeBetween('now', '+1 months'),
                'date_fin' => $faker->dateTimeBetween('+2 months', '+6 months'),
                'lieu' => $faker->city,
                'statut' => $faker->randomElement(['brouillon', 'publié', 'annulé']),
                'affiche' => $faker->imageUrl(640, 480, 'event', true), // Faux lien d'image
            ]);
        }
    }
}
