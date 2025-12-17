<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizerTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'event' => $this->titre,
            'tickets' => $this->billets->groupBy('type')->map(function ($tickets, $type) {
                return [
                    'nom' => $type,
                    'prix' => $tickets->first()->prix,
                    'vendus' => $tickets->count(),
                    'total' => $tickets->first()->quantite,
                ];
            })->values(),
        ];
    }
}
