<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketRessource extends JsonResource
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
            'type' => $this->type,
            'prix' => $this->prix,
            'quantite_disponible' => $this->quantité_disponible,
            'quantite_restante' => $this->quantite_restante,
            'date_limite_vente' => $this->date_limite_vente,
            'image_url' => $this->image ? : null,
            'event_id' => $this->event_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
