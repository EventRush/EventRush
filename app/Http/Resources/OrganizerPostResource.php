<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizerPostResource extends JsonResource
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
            'title' => $this->titre,
            'subtitle' => $this->subtitle,
            'content' => $this->contenu,
            'image' => $this->image,
            'date' => $this->created_at->toISOString(),
            'likes' => $this->likes_count,
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
