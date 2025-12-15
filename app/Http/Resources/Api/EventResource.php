<?php

namespace App\Http\Resources\Api;

use App\Models\OrganisateurProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $organisateur = $this->organisateur;//OrganisateurProfile::where('utilisateur_id', $this->utilisateur_id)->first();

        // Calculs liés aux tickets
        $tickets = $this->tickets ?? collect();
        $nombre_types_ticket = $tickets->count() ?? 0;
        $nombre_places_total = $tickets->sum('quantité_disponible') ?? 0;
        $nombre_places_restantes = $tickets->sum('quantite_restante') ?? 0;
        // ✅ Prix du ticket le moins cher
        $prix_min_ticket = $tickets->min('prix') ?? null; 

        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'date'      => $this->date_debut . ' - ' . $this->date_fin,
            'lieu' => $this->lieu,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'statut' => $this->statut,
            'affiche_url' => $this->affiche ?: null,
            'image' => $this->affiche ?: null,
            'points' => $this->points,
            'nbr_achat' => $this->nbr_achat,
            'photos' => $this->photos->map(function ($photo) {
                return $photo->image_path;
            }),
            'organisateur' => $organisateur ? [
                'id' => $organisateur->id,
                'nom_entreprise' => $organisateur->nom_entreprise,
                'logo' => $organisateur->logo ?: null,
            ] : null,

            // ✅ Nouveaux champs liés aux tickets
            'price' => $prix_min_ticket,
            'nombre_types_ticket' => $nombre_types_ticket,
            'nombre_places_total' => $nombre_places_total,
            'nombre_places_restantes' => $nombre_places_restantes,
        ];
    }

    // public function toArray(Request $request): array
    // {
    //     $organisateur = OrganisateurProfile::where('utilisateur_id', $this->utilisateur_id)->first();
    //     return [
    //         'id' => $this->id,
    //         'titre' => $this->titre,
    //         'description' => $this->description,
    //         'date_debut' => $this->date_debut,
    //         'date_fin' => $this->date_fin,
    //         'lieu' => $this->lieu,
    //         'latitude' => $this->latitude,
    //         'longitude' => $this->longitude,
    //         'statut' => $this->statut,
    //         'affiche_url' => $this->affiche ?  : null,
    //         'points' => $this->points,
    //         'nbr_achat' => $this->nbr_achat,
    //         'photos' => $this->photos->map(function ($photo) {
    //             return $photo->image_path;
    //         }),
    //         'organisateur' =>  $organisateur ? [
    //             'id' => $organisateur->id,
    //             'nom_entreprise' => $organisateur->nom_entreprise,
    //             'logo' => $organisateur->logo ?  : null,
    //         ]: null,
    //     ];
    // }
    //     return [
    //         'id' => $this->id,
    //         'titre' => $this->titre,
    //         'description' => $this->description,
    //         'date_debut' => $this->date_debut,
    //         'date_fin' => $this->date_fin,
    //         'lieu' => $this->lieu,
    //         'statut' => $this->statut,
    //         'organizer' => new OrganisateurResource($this->whenLoaded('organizer')),
    //         'created_at' => $this->created_at,
    //     ];
    // }
    //     return parent::toArray($request);
    // }
}
