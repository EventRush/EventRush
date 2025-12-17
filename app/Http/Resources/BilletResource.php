<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BilletResource extends JsonResource
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
            'event' => [
                'nom' => $this->event->titre,
                'date' => $this->event->date_debut,
            ],
            'type' => $this->type,
            'qr_code' => $this->qr_code,
        ];
    }
}
