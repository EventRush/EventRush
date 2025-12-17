<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Badge;
use App\Models\Commentaire;
use App\Models\CommunityPost;
use App\Models\Event;
use App\Models\EventHighlight;
use App\Models\EventPost;
use App\Models\LifestylePost;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Promo;
use App\Models\Reaction;
use App\Models\Share;
use App\Models\Storie;
use App\Models\Ticket;
use App\Models\Utilisateur;
use App\Models\UtilisateurBadge;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // -------------------------------------------
        // 1️⃣ UTILISATEURS (1 → 6)
        // -------------------------------------------
        // $admin = Utilisateur::updateOrCreate(
        //     ['email' => 'towanouluc@gmail.com'],
        //     [
        //         'nom' => 'Admin Principal',
        //         'password' => Hash::make('Chrislenne175'),
        //         'role' => 'admin',
        //         'email_verified_at' => now(),
        //     ]
        // );

        // $users = Utilisateur::insert([
        //     [
        //         'nom' => 'Merveille',
        //         'email' => 'merveille@gmail.com',
        //         'password' => Hash::make('Faker0001'),
        //         'role' => 'organisateur',
        //         'email_verified_at' => now(),
        //     ],
        //     [
        //         'nom' => 'Socrates',
        //         'email' => 'socrates@gmail.com',
        //         'password' => Hash::make('Faker0002'),
        //         'role' => 'organisateur',
        //         'email_verified_at' => now(),
        //     ],
        //     [
        //         'nom' => 'Samuel',
        //         'email' => 'samuel@gmail.com',
        //         'password' => Hash::make('Faker0003'),
        //         'role' => 'client',
        //         'email_verified_at' => now(),
        //     ],
        //     [
        //         'nom' => 'Malik',
        //         'email' => 'malik@gmail.com',
        //         'password' => Hash::make('Faker0004'),
        //         'role' => 'client',
        //         'email_verified_at' => now(),
        //     ],
        //     [
        //         'nom' => 'Femi',
        //         'email' => 'femi@gmail.com',
        //         'password' => Hash::make('Faker0005'),
        //         'role' => 'client',
        //         'email_verified_at' => now(),
        //     ],
        // ]);
        // echo('starting');
        // $allUsers = Utilisateur::take(6)->get();

        // // -------------------------------------------
        // // 2️⃣ EVENTS DÉJA EN BASE
        // // -------------------------------------------
        // $events = Event::all();
        // echo('fetsh');
        // // -------------------------------------------
        // // 3️⃣ BADGES
        // // -------------------------------------------
        // // $badge1 = Badge::create([
        // //     'nom' => 'Early Supporter',
        // //     'icone' => 'star',
        // //     'description' => 'Utilisateur fidèle depuis le début.'
        // // ]);

        // // $badge2 = Badge::create([
        // //     'nom' => 'Top Reviewer',
        // //     'icone' => 'medal',
        // //     'description' => 'A laissé plus de 10 commentaires.'
        // // ]);
        // $badges = [
        //     [
        //         'nom' => 'Early Supporter',
        //         'icone' => 'star',
        //         'description' => 'Utilisateur fidèle depuis le début.'
        //     ],
        //     [
        //         'nom' => 'Top Reviewer',
        //         'icone' => 'medal',
        //         'description' => 'A laissé plus de 10 commentaires.'
        //     ],
        //     [
        //         'nom' => 'Social Butterfly',
        //         'icone' => 'butterfly',
        //         'description' => 'A ajouté plus de 5 amis dans l’app.'
        //     ],
        //     [
        //         'nom' => 'Night Owl',
        //         'icone' => 'moon',
        //         'description' => 'A participé à un événement après minuit.'
        //     ],
        //     [
        //         'nom' => 'Event Explorer',
        //         'icone' => 'compass',
        //         'description' => 'A découvert 5 nouvelles catégories d’événements.'
        //     ],
        //     [
        //         'nom' => 'Photo Star',
        //         'icone' => 'camera',
        //         'description' => 'A partagé 3 photos d’événements.'
        //     ],
        //     [
        //         'nom' => 'VIP',
        //         'icone' => 'crown',
        //         'description' => 'A acheté un billet premium.'
        //     ],
        //     [
        //         'nom' => 'Organizer Rookie',
        //         'icone' => 'calendar',
        //         'description' => 'A créé son premier événement.'
        //     ],
        //     [
        //         'nom' => 'Marathoner',
        //         'icone' => 'shoe',
        //         'description' => 'A participé à 10 événements consécutifs.'
        //     ],
        //     [
        //         'nom' => 'Trend Setter',
        //         'icone' => 'fire',
        //         'description' => 'Son événement a attiré plus de 50 participants.'
        //     ],
        // ];
        // $createdBadges = [];
        // echo('creating');
        // foreach ($badges as $badge) {
        //     $createdBadges[] = Badge::create($badge);
        //     // echo('starting');
        // }

        // foreach ($allUsers as $u) {
        //     UtilisateurBadge::create([
        //         'utilisateur_id' => $u->id,
        //         'badge_id' => $createdBadges[0]->id
        //     ]);
        //     if ($u->id = [2,3,5,6]) {
        //         // UtilisateurBadge::create([
        //         //     'utilisateur_id' => $u->id,
        //         //     'badge_id' => $createdBadges[2]->id
        //         // ]);
        //         // UtilisateurBadge::create([
        //         //     'utilisateur_id' => $u->id,
        //         //     'badge_id' => $createdBadges[2]->id
        //         // ]);
        //         foreach ($createdBadges as $bg) {
        //             if ($bg->id = [2,5,6,9,]) {
        //                 UtilisateurBadge::create([
        //                     'utilisateur_id' => $u->id,
        //                     'badge_id' => $bg->id
        //                 ]);
        //             }
        //         }
        //     }
        //     if ($u->id = [2,3,4]) {
        //         foreach ($createdBadges as $bg) {
        //             if ($bg->id = [4,7,8,10]) {
        //                 UtilisateurBadge::create([
        //                     'utilisateur_id' => $u->id,
        //                     'badge_id' => $bg->id
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // $images = [
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758718284/uockjx3la6a66iggzz4w.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758719546/zjhtz1f4j3g9xisfbfmt.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758720120/zcecflrpsgwv1ctxll3o.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758721365/tzofr5nmhovsq0wapw9e.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1750162229/kiptnw2ggx35wc84u7oe.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758722410/wjx3q8w11vcx5df5htqa.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758717067/jty9bzydvzsgtl8op47d.jpg",
        //     "https://res.cloudinary.com/dzdbedjc8/image/upload/v1751276248/e5gbpuhz8ahfkggvijur.png"
        // ] ;
        // $videos = [
        //     "app/public/video_liste_event/anime_01",
        //     "app/public/video_liste_event/baman_01",
        //     "app/public/video_liste_event/blue_01",
        //     "app/public/video_liste_event/cat_01",
        //     "app/public/video_liste_event/cat_02",
        //     "app/public/video_liste_event/cat_03",
        //     "app/public/video_liste_event/couple_01",
        //     "app/public/video_liste_event/crokmore_game_01",
        //     "app/public/video_liste_event/disney_01",
        //     "app/public/video_liste_event/disney_02",
        //     "app/public/video_liste_event/disney_03",
        //     "app/public/video_liste_event/fire_01",
        //     "app/public/video_liste_event/hybou_01",
        //     "app/public/video_liste_event/licorne_01",
        //     "app/public/video_liste_event/loup_01",
        //     "app/public/video_liste_event/loup_02",
        //     "app/public/video_liste_event/lufi_01",
        //     "app/public/video_liste_event/nature_01",
        //     "app/public/video_liste_event/nature_02",
        //     "app/public/video_liste_event/nature_03",
        //     "app/public/video_liste_event/nature_04",
        //     "app/public/video_liste_event/nature_05",
        //     "app/public/video_liste_event/nature_06"
        // ];
        // // -------------------------------------------
        // // 4️⃣ EVENT POSTS
        // // -------------------------------------------
        // echo 'starting event';
        // $n = 0;
        // $b = 0;
        // foreach ($events as $event) {
        //     $user = $event->utilisateur;
            
        //     if ($event->id % 7 === 0 ) {
        //         EventPost::create([
        //             'event_id' => $event->id,
        //             'utilisateur_id' => 2,
        //             'titre' => "Annonce pour l'événement : $event->titre", 
        //             'contenu' => "Découvrez cet événement incroyable !",
        //             'image' => $images[$n],
        //         ]);
        //         $n ++  ;
        //         if ( $n = 10 ) $n = 0;
        //     }
        //     if ($event->id % 11 === 0 ) {
        //         EventPost::create([
        //             'event_id' => $event->id,
        //             'utilisateur_id' => 3,
        //             'titre' => "Annonce pour l'événement : $event->titre", 
        //             'contenu' => "Découvrez cet événement incroyable !",
        //             'image' => $images[$b],
        //         ]);
        //         $b ++  ;
        //         if ( $b = 10 ) $b = 0;
        //         if ($event->id % 3 === 0 ) {
        //             EventPost::create([
        //                 'event_id' => $event->id,
        //                 'utilisateur_id' => 2,
        //                 'titre' => "Annonce pour l'événement : $event->titre", 
        //                 'contenu' => "Découvrez cet événement incroyable !",
        //                 'image' => $images[$n],
        //             ]);
        //         }
        //     }
        // }

        // // -------------------------------------------
        // // 5️⃣ STORIES
        // // -------------------------------------------
        // foreach ($allUsers as $u) {
        //     Storie::create([
        //         'utilisateur_id' => $u->id,
        //         'media_path' => 'stories/story1.jpg',
        //         'expires_at' => now()->addHours(24),
        //     ]);
        // }

        // // -------------------------------------------
        // // 6️⃣ HIGHLIGHTS (Reels)
        // // -------------------------------------------
        // $n = 0;
        // foreach ($events as $event) {
        //     if ($event->id % 7 === 0 ) {
        //         EventHighlight::create([
        //             'utilisateur_id' => 4,
        //             'event_id' => $event->id,
        //             'description' => 'Best highlight of the event!',
        //             'media_path' => Cloudinary::upload($videos[$n]->file('affiche')->getRealPath())->getSecurePath(),
        //             'type' => 'video'
        //         ]);

        //         if ($event->id % 17 === 0 ) {
        //             EventHighlight::create([
        //                 'utilisateur_id' => 2,
        //                 'event_id' => $event->id,
        //                 'description' => 'Best highlight of the event!',
        //                 'media_path' => Cloudinary::upload($videos[$n]->file('affiche')->getRealPath())->getSecurePath(),
        //                 'type' => 'video'
        //             ]);
        //         }
        //     }
            
        //     $n ++  ;
        //     if ( $n = 9 ) $n = 0;
        // }

        // // -------------------------------------------
        // // 7️⃣ ARTICLES
        // // -------------------------------------------
        // Article::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Comment préparer un bon événement ?',
        //     'excerpt' => 'Quelques astuces simples mais efficaces.',
        //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
        //     'cover' => 'images/article1.jpg',
        //     'read_time' => '3 min',
        // ]);

        // Article::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Les 5 erreurs à éviter lors d’un concert',
        //     'excerpt' => 'Organisation, communication et ambiance : ne ratez rien.',
        //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
        //     'cover' => 'images/article2.jpg',
        //     'read_time' => '4 min',
        // ]);

        // Article::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Créer une communauté autour de vos événements',
        //     'excerpt' => 'Fidéliser et engager vos participants.',
        //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
        //     'cover' => 'images/article3.jpg',
        //     'read_time' => '5 min',
        // ]);

        // // -------------------------------------------
        // // 8️⃣ PROMOS
        // // -------------------------------------------
        // Promo::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Réduction spéciale',
        //     'caption' => '-20% sur les tickets premium',
        //     'image' => 'images/promo1.jpg',
        //     'type' => 'offer',
        // ]);

        // Promo::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Offre de Noël',
        //     'caption' => '🎄 -30% sur tous les billets jusqu’au 25 décembre',
        //     'image' => 'images/promo2.jpg',
        //     'type' => 'seasonal',
        // ]);

        // Promo::create([
        //     'utilisateur_id' => 1,
        //     'title' => 'Happy Hour',
        //     'caption' => '2 billets achetés = 1 offert entre 18h et 20h',
        //     'image' => 'images/promo3.jpg',
        //     'type' => 'flash',
        // ]);

        // // -------------------------------------------
        // // 9️⃣ COMMUNITY POSTS
        // // -------------------------------------------
        // foreach ($allUsers as $u) {
        //     if ($u->id != 1 & $u->id != 2 & $u->id != 3)
        //     {
        //         CommunityPost::create([
        //             'utilisateur_id' => $u->id,
        //             'contenu' => "Je suis super excité pour les nouveaux events !",
        //             'image' => null
        //         ]);
        //     }

        //     if ($u->id = 3)
        //     {
        //         CommunityPost::create([
        //             'utilisateur_id' => 4,
        //             'contenu' => "Les event de $u->nom sont vraiment ice!",
        //             'image' => null
        //         ]);
        //         CommunityPost::create([
        //             'utilisateur_id' => 6,
        //             'contenu' => "Les event de $u->nom sont vraiment ice!",
        //             'image' => null
        //         ]);
        //     }

        // }

        // // -------------------------------------------
        // // 🔟 LIFESTYLE POSTS
        // // -------------------------------------------
        // LifestylePost::create([
        //     'title' => "Backstage d'un concert à Cotonou",
        //     'image' => $images[5],
        //     'category' => 'backstage',
        // ]);

        // LifestylePost::create([
        //     'title' => "Citation du jour",
        //     'image' => $images[6],
        //     'category' => 'quote',
        // ]);

        // // -------------------------------------------
        // // 1️⃣1️⃣ POLLS + OPTIONS
        // // -------------------------------------------
        // $poll = Poll::create([
        //     'question' => 'Quel type d’événement préférez-vous ?',
        //     'type' => 'poll',
        // ]);

        // foreach (['Concerts', 'Expositions', 'Soirées', 'Spectacles'] as $opt) {
        //     PollOption::create([
        //         'poll_id' => $poll->id,
        //         'text' => $opt
        //     ]);
        // }

        // // -------------------------------------------
        // // 1️⃣2️⃣ COMMENTAIRES (polymorph)
        // // -------------------------------------------
        // foreach ($events as $event) {
        //     Commentaire::create([
        //         'utilisateur_id' => 3,
        //         'commentable_id' => $event->id,
        //         'commentable_type' => Event::class,
        //         'contenu' => "Super event, j'ai adoré !",
        //         'note' => 5
        //     ]);
        //     Commentaire::create([
        //         'utilisateur_id' => 5,
        //         'commentable_id' => $event->id,
        //         'commentable_type' => Event::class,
        //         'contenu' => "Boff !",
        //         'note' => 2
        //     ]);
        // }

        // // -------------------------------------------
        // // 1️⃣3️⃣ REACTIONS (morph)
        // // -------------------------------------------
        // foreach ($events as $event) {
        //     Reaction::create([
        //         'utilisateur_id' => 2,
        //         'type' => 'like',
        //         'reactable_id' => $event->id,
        //         'reactable_type' => Event::class,
        //     ]);
        //     Reaction::create([
        //         'utilisateur_id' => 4,
        //         'type' => 'love',
        //         'reactable_id' => $event->id,
        //         'reactable_type' => Event::class,
        //     ]);
        //     Reaction::create([
        //         'utilisateur_id' => 5,
        //         'type' => 'sad',
        //         'reactable_id' => $event->id,
        //         'reactable_type' => Event::class,
        //     ]);
        //     Reaction::create([
        //         'utilisateur_id' => 6,
        //         'type' => 'haha',
        //         'reactable_id' => $event->id,
        //         'reactable_type' => Event::class,
        //     ]);
        //     Reaction::create([
        //         'utilisateur_id' => 3,
        //         'type' => 'wow',
        //         'reactable_id' => $event->id,
        //         'reactable_type' => Event::class,
        //     ]);
        // }

        // // -------------------------------------------
        // // 1️⃣4️⃣ SHARES (morph)
        // // -------------------------------------------
        // $n = 0;
        // $b = 0;
        // foreach ($events as $event) {
        //     if ($event->id % 8 === 0) {
        //         Share::create([
        //             'utilisateur_id' => 6,
        //             'shareable_id' => $event->id,
        //             'shareable_type' => Event::class,
        //         ]);
        //         if ($event->id % 5 === 0) {
        //             Share::create([
        //                 'utilisateur_id' => 3,
        //                 'shareable_id' => $event->id,
        //                 'shareable_type' => Ticket::class,
        //             ]);

        //             Share::create([
        //                 'utilisateur_id' => 4,
        //                 'shareable_id' => $event->id,
        //                 'shareable_type' => EventPost::class,
        //             ]);
        //         }
        //     }
        //     $n ++ ;
        //     $b ++ ;

        // }

        DB::beginTransaction();

        try {
            echo('starting');

            $allUsers = Utilisateur::take(6)->get();

            // -------------------------------------------
            // 2️⃣ EVENTS DÉJA EN BASE
            // -------------------------------------------
            $events = Event::all();
            echo('fetsh');

            // -------------------------------------------
            // 3️⃣ BADGES
            // -------------------------------------------
            $badges = [
                ['nom' => 'Early Supporter','icone' => 'star','description' => 'Utilisateur fidèle depuis le début.'],
                ['nom' => 'Top Reviewer','icone' => 'medal','description' => 'A laissé plus de 10 commentaires.'],
                ['nom' => 'Social Butterfly','icone' => 'butterfly','description' => 'A ajouté plus de 5 amis dans l’app.'],
                ['nom' => 'Night Owl','icone' => 'moon','description' => 'A participé à un événement après minuit.'],
                ['nom' => 'Event Explorer','icone' => 'compass','description' => 'A découvert 5 nouvelles catégories d’événements.'],
                ['nom' => 'Photo Star','icone' => 'camera','description' => 'A partagé 3 photos d’événements.'],
                ['nom' => 'VIP','icone' => 'crown','description' => 'A acheté un billet premium.'],
                ['nom' => 'Organizer Rookie','icone' => 'calendar','description' => 'A créé son premier événement.'],
                ['nom' => 'Marathoner','icone' => 'shoe','description' => 'A participé à 10 événements consécutifs.'],
                ['nom' => 'Trend Setter','icone' => 'fire','description' => 'Son événement a attiré plus de 50 participants.'],
            ];

            $createdBadges = [];
            echo('creating');
            foreach ($badges as $badge) {
                $createdBadges[] = Badge::create($badge);
                // echo('starting');
            }

            // Exemple d’association badges ↔ utilisateurs
            foreach ($allUsers as $u) {
                UtilisateurBadge::create([
                    'utilisateur_id' => $u->id,
                    'badge_id' => $createdBadges[0]->id
                ]);
                    if (in_array($u->id, [2,3,5,6])) {
                    
                    foreach ($createdBadges as $bg) {
                        if (in_array($bg->id, [2,5,6,9,])) {
                            UtilisateurBadge::create([
                                'utilisateur_id' => $u->id,
                                'badge_id' => $bg->id
                            ]);
                        }
                    }
                }
                if (in_array($u->id,[2,3,4])) {
                    foreach ($createdBadges as $bg) {
                        if (in_array($bg->id, [4,7,8,10])) {
                            UtilisateurBadge::create([
                                'utilisateur_id' => $u->id,
                                'badge_id' => $bg->id
                            ]);
                        }
                    }
                }
            }
            $images = [
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758718284/uockjx3la6a66iggzz4w.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758719546/zjhtz1f4j3g9xisfbfmt.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758720120/zcecflrpsgwv1ctxll3o.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758721365/tzofr5nmhovsq0wapw9e.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1750162229/kiptnw2ggx35wc84u7oe.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758722410/wjx3q8w11vcx5df5htqa.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1758717067/jty9bzydvzsgtl8op47d.jpg",
            "https://res.cloudinary.com/dzdbedjc8/image/upload/v1751276248/e5gbpuhz8ahfkggvijur.png"
        ] ;
        $videos = [
            "app/public/video_liste_event/anime_01.mp4",
            "app/public/video_liste_event/baman_01.mp4",
            "app/public/video_liste_event/blue_01.mp4",
            "app/public/video_liste_event/cat_01.mp4",
            "app/public/video_liste_event/cat_02.mp4",
            "app/public/video_liste_event/cat_03.mp4",
            "app/public/video_liste_event/couple_01.mp4",
            "app/public/video_liste_event/crokmore_game_01.mp4",
            "app/public/video_liste_event/disney_01.mp4",
            "app/public/video_liste_event/disney_02.mp4",
            "app/public/video_liste_event/disney_03.mp4",
            "app/public/video_liste_event/fire_01.mp4",
            "app/public/video_liste_event/hybou_01.mp4",
            "app/public/video_liste_event/licorne_01.mp4",
            "app/public/video_liste_event/loup_01.mp4",
            "app/public/video_liste_event/loup_02.mp4",
            "app/public/video_liste_event/lufi_01.mp4",
            "app/public/video_liste_event/nature_01.mp4",
            "app/public/video_liste_event/nature_02.mp4",
            "app/public/video_liste_event/nature_03.mp4",
            "app/public/video_liste_event/nature_04.mp4",
            "app/public/video_liste_event/nature_05.mp4",
            "app/public/video_liste_event/nature_06.mp4",
        ];


        // -------------------------------------------
        // 4️⃣ EVENT POSTS
        // -------------------------------------------
        echo 'starting eventPost';
        $n = 0;
        $b = 0;
        foreach ($events as $event) {
            // $user = $event->utilisateur;
            
            if ($event->id % 7 === 0 ) {
                EventPost::create([
                    'event_id' => $event->id,
                    'utilisateur_id' => 2,
                    'titre' => "Annonce pour l'événement : $event->titre", 
                    'contenu' => "Découvrez cet événement incroyable !",
                    'image' => $images[$n],
                ]);
                $n ++  ;
                if ( $n = 10 ) $n = 0;
            }
            if ($event->id % 11 === 0 ) {
                EventPost::create([
                    'event_id' => $event->id,
                    'utilisateur_id' => 3,
                    'titre' => "Annonce pour l'événement : $event->titre", 
                    'contenu' => "Découvrez cet événement incroyable !",
                    'image' => $images[$b],
                ]);
                $b ++  ;
                if ( $b = 10 ) $b = 0;
                if ($event->id % 3 === 0 ) {
                    EventPost::create([
                        'event_id' => $event->id,
                        'utilisateur_id' => 2,
                        'titre' => "Annonce pour l'événement : $event->titre", 
                        'contenu' => "Découvrez cet événement incroyable !",
                        'image' => $images[$n],
                    ]);
                }
            }
        }

        // -------------------------------------------
        // 5️⃣ STORIES
        // -------------------------------------------
        echo "starting stories" ;
        $n = 0;
        $tv = count($videos);
        $ti = count($images);
        echo "taille video : $tv \n taille image :  $ti \n";
        foreach ($allUsers as $u) {
            if (isset($videos[$n])) {
                echo "$videos[$n]\n";
                $v = $videos[$n];
                echo "$v";
                $path = storage_path($v);
                echo "\n Full path: $path \n";
                echo "exists: " . (file_exists($path) ? 'YES' : 'NO');
                $response = Cloudinary::upload($path,[
                                                        'resource_type' => 'auto'
                                                    ]);
                print_r($response);
                $secureUrl   = $response->getSecurePath();   // URL sécurisée
                $publicId    = $response->getPublicId();     // Identifiant unique
                $format      = $response->getFormat();       // Extension (mp4, jpg…)
                $resource    = $response->getResourceType(); // "video" ou "image"
                echo ("secureUrl : $secureUrl \n publicId: $publicId\n format : $format\n resource : $resource");
                // $path = Cloudinary::upload($v)->getSecurePath();;
                // echo "$path";
            Storie::create([
                'utilisateur_id' => $u->id,
                'media_path' => $secureUrl,
                'expires_at' => now()->addHours(144),
            ]);
            }
            if (isset($images[$n])) { 
                echo "$images[$n]";
                Storie::create([
                'utilisateur_id' => $u->id,
                'media_path' => $images[$n],
                'expires_at' => now()->addHours(144),
            ]);}
            $n ++;
            if ($n >= count($images)) {
                $n = 0;
            }
        }

        // -------------------------------------------
        // 6️⃣ HIGHLIGHTS (Reels)
        // -------------------------------------------
        echo "starting highlight" ;
        $n = 1;
        foreach ($events as $event) {
            if ($event->id % 7 === 0 ) {
                EventHighlight::create([
                    'utilisateur_id' => 4,
                    'event_id' => $event->id,
                    'description' => 'Best highlight of the event!',
                    'media_path' => Cloudinary::upload($videos[$n]->file('highlight')->getRealPath())->getSecurePath(),
                    'type' => 'video'
                ]);

                if ($event->id % 17 === 0 ) {
                    EventHighlight::create([
                        'utilisateur_id' => 2,
                        'event_id' => $event->id,
                        'description' => 'Best highlight of the event!',
                        'media_path' => Cloudinary::upload($videos[$n]->file('affiche')->getRealPath())->getSecurePath(),
                        'type' => 'video'
                    ]);
                }
            }
            
            $n ++  ;
            if ( $n = 9 ) $n = 0;
        }
            // -------------------------------------------
            // 7️⃣ ARTICLES
            // -------------------------------------------
            Article::create([
                'utilisateur_id' => 1,
                'title' => 'Comment préparer un bon événement ?',
                'excerpt' => 'Quelques astuces simples mais efficaces.',
                'content' => 'Lorem ipsum dolor sit amet...',
                'cover' => $images[0],
                'read_time' => '3 min',
            ]);

            Article::create([
                'utilisateur_id' => 1,
                'title' => 'Les 5 erreurs à éviter lors d’un concert',
                'excerpt' => 'Organisation, communication et ambiance : ne ratez rien.',
                'content' => 'Lorem ipsum dolor sit amet...',
                'cover' => $images[1],
                'read_time' => '4 min',
            ]);

            Article::create([
                'utilisateur_id' => 1,
                'title' => 'Créer une communauté autour de vos événements',
                'excerpt' => 'Fidéliser et engager vos participants.',
                'content' => 'Lorem ipsum dolor sit amet...',
                'cover' => $images[2],
                'read_time' => '5 min',
            ]);

            // -------------------------------------------
            // 8️⃣ PROMOS
            // -------------------------------------------
            Promo::create([
                'utilisateur_id' => 1,
                'title' => 'Réduction spéciale',
                'caption' => '-20% sur les tickets premium',
                'image' => $images[3],
                'type' => 'offer',
            ]);

            Promo::create([
                'utilisateur_id' => 1,
                'title' => 'Offre de Noël',
                'caption' => '🎄 -30% sur tous les billets jusqu’au 25 décembre',
                'image' => $images[4],
                'type' => 'seasonal',
            ]);

            Promo::create([
                'utilisateur_id' => 1,
                'title' => 'Happy Hour',
                'caption' => '2 billets achetés = 1 offert entre 18h et 20h',
                'image' => $images[5],
                'type' => 'flash',
            ]);

            // -------------------------------------------
        // 9️⃣ COMMUNITY POSTS
        // -------------------------------------------
        foreach ($allUsers as $u) {
            if ($u->id != 1 & $u->id != 2 & $u->id != 3)
            {
                CommunityPost::create([
                    'utilisateur_id' => $u->id,
                    'contenu' => "Je suis super excité pour les nouveaux events !",
                    'image' => null
                ]);
            }

            if ($u->id = 3)
            {
                CommunityPost::create([
                    'utilisateur_id' => 4,
                    'contenu' => "Les event de $u->nom sont vraiment ice!",
                    'image' => null
                ]);
                CommunityPost::create([
                    'utilisateur_id' => 6,
                    'contenu' => "Les event de $u->nom sont vraiment ice!",
                    'image' => null
                ]);
            }

        }

        // -------------------------------------------
        // 🔟 LIFESTYLE POSTS
        // -------------------------------------------
        LifestylePost::create([
            'title' => "Backstage d'un concert à Cotonou",
            'image' => $images[5],
            'category' => 'backstage',
        ]);

        LifestylePost::create([
            'title' => "Citation du jour",
            'image' => $images[6],
            'category' => 'quote',
        ]);

        // -------------------------------------------
        // 1️⃣1️⃣ POLLS + OPTIONS
        // -------------------------------------------
        $poll = Poll::create([
            'question' => 'Quel type d’événement préférez-vous ?',
            'type' => 'poll',
        ]);

        foreach (['Concerts', 'Expositions', 'Soirées', 'Spectacles'] as $opt) {
            PollOption::create([
                'poll_id' => $poll->id,
                'text' => $opt
            ]);
        }

        // -------------------------------------------
        // 1️⃣2️⃣ COMMENTAIRES (polymorph)
        // -------------------------------------------
        foreach ($events as $event) {
            Commentaire::create([
                'utilisateur_id' => 3,
                'commentable_id' => $event->id,
                'commentable_type' => Event::class,
                'contenu' => "Super event, j'ai adoré !",
                'note' => 5
            ]);
            Commentaire::create([
                'utilisateur_id' => 5,
                'commentable_id' => $event->id,
                'commentable_type' => Event::class,
                'contenu' => "Boff !",
                'note' => 2
            ]);
        }

        // -------------------------------------------
        // 1️⃣3️⃣ REACTIONS (morph)
        // -------------------------------------------
        foreach ($events as $event) {
            Reaction::create([
                'utilisateur_id' => 2,
                'type' => 'like',
                'reactable_id' => $event->id,
                'reactable_type' => Event::class,
            ]);
            Reaction::create([
                'utilisateur_id' => 4,
                'type' => 'love',
                'reactable_id' => $event->id,
                'reactable_type' => Event::class,
            ]);
            Reaction::create([
                'utilisateur_id' => 5,
                'type' => 'sad',
                'reactable_id' => $event->id,
                'reactable_type' => Event::class,
            ]);
            Reaction::create([
                'utilisateur_id' => 6,
                'type' => 'haha',
                'reactable_id' => $event->id,
                'reactable_type' => Event::class,
            ]);
            Reaction::create([
                'utilisateur_id' => 3,
                'type' => 'wow',
                'reactable_id' => $event->id,
                'reactable_type' => Event::class,
            ]);
        }

        // -------------------------------------------
        // 1️⃣4️⃣ SHARES (morph)
        // -------------------------------------------
        $n = 0;
        $b = 0;
        foreach ($events as $event) {
            if ($event->id % 8 === 0) {
                Share::create([
                    'utilisateur_id' => 6,
                    'shareable_id' => $event->id,
                    'shareable_type' => Event::class,
                ]);
                if ($event->id % 5 === 0) {
                    Share::create([
                        'utilisateur_id' => 3,
                        'shareable_id' => $event->id,
                        'shareable_type' => Ticket::class,
                    ]);

                    Share::create([
                        'utilisateur_id' => 4,
                        'shareable_id' => $event->id,
                        'shareable_type' => EventPost::class,
                    ]);
                }
            }
            $n ++ ;
            $b ++ ;

        }

            // -------------------------------------------
            // ✅ Commit si tout est OK
            // -------------------------------------------
            DB::commit();
            echo('Transaction réussie');
        } catch (\Exception $e) {
            // -------------------------------------------
            // ❌ Rollback si erreur
            // -------------------------------------------
            DB::rollback();
            echo('Erreur : ' . $e->getMessage());
        }
    }
}
