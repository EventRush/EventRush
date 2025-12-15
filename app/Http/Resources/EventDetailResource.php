<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // -- ORGANISATEUR --
        $organisateur = $this->utilisateur ?? null;

        // -- TICKETS --
        $tickets = $this->tickets;
        $nombre_types_ticket = $tickets->count();
        $nombre_places_total = $tickets->sum('quantité_disponible');
        $nombre_places_restantes = $tickets->sum('quantite_restante');

        // -- STATISTIQUES --
        $nombre_vue = \App\Models\EventVue::where('event_id', $this->id)->count();
        $nombre_favoris = $this->favorisePar()->count();
        $nombre_partages = $this->tests()->count(); // utilise ton modèle "Test" si c'est du tracking

        // -- COMMENTAIRES --
        $commentaires = $this->commentaires ?? collect();
        $nombre_commentaires = $commentaires->count();
        $moyenne_notes = $commentaires->avg('note');
        $favorised = false;
        if (request()->user()) {
            $favorised = request()->user()->favoris->contains($this->id);
        }
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'lieu' => $this->lieu,
            'date'      => $this->date_debut . ' - ' . $this->date_fin,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'statut' => $this->statut, 

            'is_favori' => $favorised ?? null,

            // Localisation
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => $this->distance ?? null,

            // Média
            'affiche_url' => $this->affiche ?? null,
            'photos' => $this->photos->map(fn($p) => [
                'id' => $p->id,
                'url' => $p->image_path ?? null,
            ]),

            // Organisateur (User + OrganisateurProfile fusionnés)
            'organisateur' => $organisateur ? [
                'id' => $organisateur->id,
                'nom_entreprise' => $organisateur->nom_entreprise,
                'logo' => $organisateur->logo ?? null,

                'utilisateur' => [
                    'id' => $this->utilisateur->id,
                    'nom' => $this->utilisateur->nom,
                    'email' => $this->utilisateur->email,
                ]
            ] : null,

            // Tickets détaillés
            'tickets' => $tickets->map(fn($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'prix' => $t->prix,
                'image' => $t->image ?? null,
                'quantite_disponible' => $t->quantité_disponible,
                'quantite_restante' => $t->quantite_restante,
                'date_limite_vente' => $t->date_limite_vente,
                'nombre_billets_vendus' => $t->billets()->count(),
            ]),

            // Résumé Tickets
            'nombre_types_ticket' => $nombre_types_ticket,
            'nombre_places_total' => $nombre_places_total,
            'nombre_places_restantes' => $nombre_places_restantes,

            // Tags
            'tags' => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'nom' => $tag->nom ?? null, // au cas où
            ]),

            // Statistiques globales
            'stats' => [
                'vues' => $nombre_vue,
                'favoris' => $nombre_favoris,
                'partages' => $nombre_partages,
                'achats' => $this->billets()->count(),
            ],

            // Commentaires
            'commentaires' => $commentaires->map(fn($c) => [
                'id' => $c->id,
                'contenu' => $c->contenu,
                'note' => $c->note,
                'date' => $c->created_at,
                'utilisateur' => [
                    'id' => $c->utilisateur->id,
                    'nom' => $c->utilisateur->nom,
                ]
            ]),
            'nombre_commentaires' => $nombre_commentaires,
            'moyenne_notes' => round($moyenne_notes, 2),

            // Autres infos
            'points' => $this->points,
            'nbr_achat' => $this->nbr_achat,
            'created_at' => $this->created_at,
        ];
    }
}
