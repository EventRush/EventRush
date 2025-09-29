<?php

namespace App\Http\Controllers\Api\utilisateur;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    //

    public function getRecommendedEvents(Request $request)
    {
        $user = Auth::user();

        $preferredTags = $user->tags()->pluck('tags.id');

        $events = Event::whereHas('tags', function ($q) use ($preferredTags) {
            $q->whereIn('tags.id', $preferredTags);
        })
        ->where('date_debut', '>=', now())
        ->with('tags')
        ->orderBy('date_debut', 'asc')
        ->get();

        return EventResource::collection($events);
    }

}
