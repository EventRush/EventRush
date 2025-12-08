<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
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
            'nom' => $this->nom,
            'icone' => $this->icone,
            'description' => $this->description,
            
            // Nombre d'utilisateurs qui possèdent ce badge (chargé si withCount est utilisé)
            'utilisateurs_count' => $this->whenCounted('utilisateurs', $this->utilisateurs_count) ?? 0,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
