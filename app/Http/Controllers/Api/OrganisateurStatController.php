<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizerResource;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class OrganisateurStatController extends Controller
{
    //
    public function organizersHub()
    {
        $organizers = Utilisateur::where('role', 'organisateur')
            ->with(['posts.comments', 'events', 'suiveurs'])
            ->get();

        $featured = $organizers->sortByDesc('points')->first();

        return response()->json([
            'featured' => new OrganizerResource($featured),

            'organizers' => OrganizerResource::collection(
                $organizers->where('id', '!=', optional($featured)->id)
            ),

            'statistics' => [
                'organizers' => $organizers->count(),
                'events' => $organizers->sum(fn ($o) => $o->events()->count()),
                'followers' => $organizers->sum(fn ($o) => $o->suiveurs()->count()),
            ],

            'quote' => [
                'text' => "La culture ne s'hérite pas, elle se conquiert.",
                'author' => "André Malraux",
            ],
        ]);
    }
}
