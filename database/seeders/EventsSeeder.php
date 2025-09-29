<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Laravel\Prompts\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
        // DB::statement("SELECT setval(pg_get_serial_sequence('events','id'), (SELECT MAX(id) FROM events))");
        // $faker = Faker::create();
        // $user = Utilisateur::where('role', 'organisateur')->get();

        // for ($i = 0; $i < 20; $i++) {
        //     $quantity = $faker->numberBetween(50, 200);
        //     Event::create([
        //         'titre' => $faker->sentence(3),
        //         'utilisateur_id' => $user->random()->id,
        //         'description' => $faker->paragraph(5),
        //         'date_debut' => $faker->dateTimeBetween('now', '+1 months'),
        //         'date_fin' => $faker->dateTimeBetween('+2 months', '+6 months'),
        //         'lieu' => $faker->city,
        //         'statut' => $faker->randomElement(['brouillon', 'publié', 'annulé']),
        //         'affiche' => $faker->imageUrl(640, 480, 'event', true), // Faux lien d'image
        //         'points' => $faker->numberBetween(20, 100),
        //     ]);
        // }
    public function run(): void
    {
        //

         $output = new ConsoleOutput();
            $startWindow = Carbon::create(2025, 9, 27, 0, 0, 0);
            $endWindow = Carbon::create(2026, 9, 27, 23, 59, 59);

            $randDateRange = function () use ($startWindow, $endWindow) {
                $start = Carbon::createFromTimestamp(rand($startWindow->timestamp, $endWindow->timestamp));
                $days = rand(0, 6);
                $end = (clone $start)->addDays($days);
                if ($end->gt($endWindow)) {
                    $end = $endWindow;
                }
                return [
                    'date_debut' => $start->toDateString(),
                    'date_fin' => $end->toDateString(),
                ];
            };

            // 30 contextual events in Benin with Unsplash images focused on African/Black cultural scenes
            $events = [
                [
                    'utilisateur_id' => 2, 
                    'titre' => 'Vodun Days Ouidah',
                    'description' => 'Célébration culturelle et rituelle du vodun avec défilés, musiques et artisanat.',
                    'lieux' => 'Place des Esclaves, Ouidah',
                    'affiche' => 'https://images.unsplash.com/photo-1520975916485-9a7d9a6d3b4b?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3610,
                    'longitude' => 2.0857,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'FINAB - Arts du Bénin',
                    'description' => 'Expositions et marché des arts contemporains du Bénin et d’Afrique de l’Ouest.',
                    'lieux' => 'Palais des Congrès, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1504198266286-1659872e6590?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3703,
                    'longitude' => 2.3912,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Cotonou Gallery Weekend',
                    'description' => 'Vernissages, ateliers et rencontres avec artistes locaux.',
                    'lieux' => 'Haie Vive, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3667,
                    'longitude' => 2.4258,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Festival Awilé - Porto-Novo',
                    'description' => 'Expositions et danses traditionnelles pour mettre en valeur le patrimoine local.',
                    'lieux' => 'Place des Fêtes, Porto-Novo',
                    'affiche' => 'https://images.unsplash.com/photo-1505575967452-9b2b84a0b5ea?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.4969,
                    'longitude' => 2.6036,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'WeLoveYa Urban Festival',
                    'description' => 'Festival urbain multi-genre avec artistes locaux et street art.',
                    'lieux' => 'Place de l’Amazone, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1508898578281-774ac4893a5a?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3561,
                    'longitude' => 2.4125,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Festival des Masques - Porto-Novo',
                    'description' => 'Parades de masques traditionnels, ateliers et conférences patrimoniales.',
                    'lieux' => 'Centre-ville, Porto-Novo',
                    'affiche' => 'https://images.unsplash.com/photo-1544986581-efac024faf62?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.4969,
                    'longitude' => 2.6036,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Fête de l’Igname - Savalou',
                    'description' => 'Cérémonies et danses marquant la récolte et la gratitude communautaire.',
                    'lieux' => 'Savalou centre-ville',
                    'affiche' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 7.8850,
                    'longitude' => 1.8075,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Quintessence - Festival du cinéma',
                    'description' => 'Projections et rencontres du cinéma béninois et ouest-africain.',
                    'lieux' => 'Cinéma municipal, Ouidah',
                    'affiche' => 'https://images.unsplash.com/photo-1495605952596-3f49f0b49b10?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3610,
                    'longitude' => 2.0857,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Effet Graff - Street Art',
                    'description' => 'Murales, ateliers et parcours d’art urbain par artistes locaux.',
                    'lieux' => 'Quartiers créatifs, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1505765056916-6c4cf1f2f2b1?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3700,
                    'longitude' => 2.4250,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Festival du Conte - Bohicon',
                    'description' => 'Conteurs et ateliers pour la transmission orale auprès des jeunes.',
                    'lieux' => 'Maison de la Culture, Bohicon',
                    'affiche' => 'https://images.unsplash.com/photo-1533115610843-0fdf7a527b1c?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 7.1646,
                    'longitude' => 2.0625,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Salon de l’Artisanat - Cotonou',
                    'description' => 'Exposition et marché d’artisanat béninois et régional.',
                    'lieux' => 'Parc des Expositions, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3698,
                    'longitude' => 2.4187,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Jazz & Blues Festival - Cotonou',
                    'description' => 'Concerts, jam sessions et ateliers pour musiciens.',
                    'lieux' => 'Marina, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3635,
                    'longitude' => 2.4397,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Nuit des Galeries - Cotonou',
                    'description' => 'Vernissages nocturnes et performances dans les galeries locales.',
                    'lieux' => 'Haie Vive, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3667,
                    'longitude' => 2.4258,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Festival de la Danse - Parakou',
                    'description' => 'Masterclasses et spectacles de danse traditionnelle et contemporaine.',
                    'lieux' => 'Théâtre municipal, Parakou',
                    'affiche' => 'https://images.unsplash.com/photo-1526948128573-703ee1aeb6fa?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 9.3371,
                    'longitude' => 2.6309,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Salon du Livre - Cotonou',
                    'description' => 'Rencontres d’auteurs et échanges sur l’édition locale.',
                    'lieux' => 'Institut Français, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3530,
                    'longitude' => 2.3770,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Arts de la Rue - Abomey-Calavi',
                    'description' => 'Théâtre de rue, marionnettes et performances itinérantes.',
                    'lieux' => 'Rues piétonnes, Abomey-Calavi',
                    'affiche' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.5167,
                    'longitude' => 2.3200,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Pasto - Pèlerinage Ouidah',
                    'description' => 'Procession et moments touristiques autour des traditions locales.',
                    'lieux' => 'Route des Pèlerinages, Ouidah',
                    'affiche' => 'https://images.unsplash.com/photo-1496307042754-0b4f9b6d5b15?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3610,
                    'longitude' => 2.0857,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Festival Culinaire Béninois',
                    'description' => 'Dégustations et ateliers valorisant la gastronomie locale.',
                    'lieux' => 'Marché Dantokpa, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3640,
                    'longitude' => 2.4290,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Fête de la Gaani - Nikki',
                    'description' => 'Cérémonies royales et courses de chevaux traditionnelles.',
                    'lieux' => 'Enceinte royale, Nikki',
                    'affiche' => 'https://images.unsplash.com/photo-1504198453319-5ce911bafcde?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 10.1850,
                    'longitude' => 3.2070,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Festival International du Théâtre - Cotonou',
                    'description' => 'Pièces contemporaines, ateliers et rencontres professionnelles.',
                    'lieux' => 'Salles culturelles, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3700,
                    'longitude' => 2.3940,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Salon de la Mode Béninoise',
                    'description' => 'Défilés, créateurs locaux et ateliers sur la mode durable.',
                    'lieux' => 'Centre des Conventions, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1516707570263-3d3f6a9b2f2c?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3500,
                    'longitude' => 2.4000,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Jeunes Talents - Abomey',
                    'description' => 'Plateforme pour jeunes artistes émergents en musique et arts visuels.',
                    'lieux' => 'Maison de la Culture, Abomey',
                    'affiche' => 'https://images.unsplash.com/photo-1531058020387-3be344556be6?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 7.1800,
                    'longitude' => 1.9900,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Marathon de Cotonou',
                    'description' => 'Course urbaine et animations en bord de mer.',
                    'lieux' => 'Boulevard de la Marina, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3670,
                    'longitude' => 2.4290,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Festival du Film Numérique',
                    'description' => 'Compétition de courts-métrages et rencontres professionnelles.',
                    'lieux' => 'Espaces culturels, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1511203466129-824e631920d4?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3600,
                    'longitude' => 2.4200,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Journées du Patrimoine - Ouidah & Abomey',
                    'description' => 'Visites guidées et expositions autour du patrimoine local.',
                    'lieux' => 'Musées et sites historiques, Ouidah & Abomey',
                    'affiche' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3610,
                    'longitude' => 2.0857,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Jeunesse & Sport - Parakou',
                    'description' => 'Compétitions sportives et concerts pour la jeunesse locale.',
                    'lieux' => 'Stade municipal, Parakou',
                    'affiche' => 'https://images.unsplash.com/photo-1508606572321-901ea443707f?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 9.3371,
                    'longitude' => 2.6309,
                ],
                [
                    'utilisateur_id' => 3,
                    'titre' => 'Startups & Innovation - Cotonou',
                    'description' => 'Conférences, pitchs et workshops pour l’écosystème tech béninois.',
                    'lieux' => 'Centre d’Innovation, Cotonou',
                    'affiche' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 6.3615,
                    'longitude' => 2.4110,
                ],
                [
                    'utilisateur_id' => 2,
                    'titre' => 'Musique Trad. & Moderne - Bohicon',
                    'description' => 'Rencontres entre artistes traditionnels et musiciens contemporains.',
                    'lieux' => 'Place publique, Bohicon',
                    'affiche' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1400&q=80',
                    'latitude' => 7.1600,
                    'longitude' => 2.0700,
                ],
            ];
            // Initialiser la progress bar
        $progressBar = new ProgressBar($output, count($events));
        $progressBar->start();

            $now = Carbon::now();
            $rows = [];
            foreach ($events as $e) {
                $range = $randDateRange();
                $rows[] = [
                    'utilisateur_id' => $e['utilisateur_id'],
                    'titre' => $e['titre'],
                    'description' => $e['description'],
                    'date_debut' => $range['date_debut'],
                    'date_fin' => $range['date_fin'],
                    'lieu' => $e['lieux'],
                    'affiche' => $e['affiche'],
                    'latitude' => $e['latitude'],
                    'longitude' => $e['longitude'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                 // Avance la barre à chaque event préparé 
                $progressBar->advance();
            }

            DB::table('events')->insert($rows);
            
            $progressBar->finish();
            $output->writeln("\n<info>✅ " . count($events) . " événements insérés avec succès !</info>");
    
        }
}
