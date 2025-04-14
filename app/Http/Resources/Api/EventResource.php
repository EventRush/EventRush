<?php

namespace App\Http\Resources\Api;

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
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'lieu' => $this->lieu,
            'statut' => $this->statut,
            'affiche_url' => $this->affiche ? asset('storage/app/public/' . $this->affiche) : null,
            'photos' => $this->photos->map(function ($photo) {
                return asset('storage//app/public/' . $photo->image_path);
            }),
            // 'organisateur' => [
            //     'id' => $this->organisateur->id,
            //     'nom_entreprise' => $this->organisateur->nom_entreprise,
            //     'logo' => $this->organisateur->logo ? asset('storage/' . $this->organisateur->logo) : null,
            // ],
        ];
    }
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
