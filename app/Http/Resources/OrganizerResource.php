<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizerResource extends JsonResource
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
            'name' => $this->nom,
            'image' => $this->avatar,
            'description' => $this->description,
            'rating' => round($this->rank, 1),
            'events_count' => $this->events()->count(),
            'followers' => $this->suiveurs()->count(),
            'points' => $this->points,
            'posts' => OrganizerPostResource::collection(
                $this->posts()->latest()->get()
            ),
        ];
    }
}
