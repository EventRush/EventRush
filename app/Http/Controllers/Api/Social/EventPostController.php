<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPost;
use Illuminate\Http\Request;

class EventPostController extends Controller
{
    //

    // public function index($eventId)
    // {
    //     return EventPost::with(['utilisateur', 'reactions'])
    //         ->where('event_id', $eventId)
    //         ->latest()
    //         ->get();
    // }

    // public function store(Request $request, $eventId)
    // {
    //     $data = $request->validate([
    //         'titre' => 'required|string',
    //         'contenu' => 'required|string',
    //         'image' => 'nullable|string',
    //     ]);

    //     return EventPost::create([
    //         'event_id' => $eventId,
    //         'utilisateur_id' => auth()->id(),
    //         'titre' => $data['titre'],
    //         'contenu' => $data['contenu'],
    //         'image' => $data['image'] ?? null,
    //     ]);
    // }

    /**
     * List posts for an event
     * GET /events/{event}/posts
     */
    public function index($eventId)
    {
        $posts = EventPost::with(['utilisateur', 'reactions', 'shares'])
            ->where('event_id', $eventId)
            ->latest()
            ->get();

        return response()->json($posts);
    }

    /**
     * Show a single post
     */
    public function show($id)
    {
        $post = EventPost::with(['utilisateur', 'reactions', 'shares'])->findOrFail($id);
        return response()->json($post);
    }

    /**
     * Create a post for an event
     * POST /events/{event}/posts
     */
    public function store(Request $request, $eventId)
    {
        // ensure event exists
        Event::findOrFail($eventId);

        $data = $request->validate([
            'titre'   => 'required|string|max:255',
            'contenu' => 'required|string',
            'image'   => 'nullable|string',
        ]);

        $post = EventPost::create([
            'event_id'      => $eventId,
            'utilisateur_id'=> auth()->id(),
            'titre'         => $data['titre'],
            'contenu'       => $data['contenu'],
            'image'         => $data['image'] ?? null,
        ]);

        return response()->json($post->load('utilisateur'), 201);
    }

    /**
     * Update a post (only owner)
     */
    public function update(Request $request, $id)
    {
        $post = EventPost::findOrFail($id);

        if ($post->utilisateur_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'titre'   => 'sometimes|required|string|max:255',
            'contenu' => 'sometimes|required|string',
            'image'   => 'nullable|string',
        ]);

        $post->update($data);

        return response()->json($post);
    }

    /**
     * Delete a post (owner or admin)
     */
    public function destroy($id)
    {
        $post = EventPost::findOrFail($id);

        if ($post->utilisateur_id !== auth()->id() ) { // && !auth()->user()->isAdmin()
            $user = auth()->user();
            if ($user->role != 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $post->delete();

        return response()->json(['status' => 'deleted']);
    }

}
