<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'nom' => $this->nom, // J'assume qu'un champ 'nom' existe pour le Tag
            'event_id' => $this->event_id,
            'utilisateur_id' => $this->utilisateur_id,
            
            // Nombre de relations, chargées si withCount est utilisé
            'events_count' => $this->whenCounted('events', $this->events_count) ?? 0,
            'utilisateurs_count' => $this->whenCounted('utilisateurs', $this->utilisateurs_count) ?? 0,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
