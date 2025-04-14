<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    public function index()
    {
        // $events = Event::with('organisateur')->latest()->get();
        $events = Event::latest()->get();
        return EventResource::collection($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            
            // 'organisateur_id' => 'required|exists:organisateur_profiles,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'statut' => 'in:brouillon,publié,annulé',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'affiche' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);
        // $validated['organisateur_id'] = auth()->user()->organisateurProfile->id;

            if ($request->hasFile('affiche')) {
                $validated['affiche'] = $request->file('affiche')->store('events/affiches', 'public');   
            }
            $event = Event::create($validated);
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('events/photos', 'public');
                    $event->photos()->create(['image_path' => $photoPath]);
                }
            }
            return new EventResource($event->load('photos') );

        // return new EventResource($event->load('photos', 'organisateur') );
    }

    public function show(Event $event)
    {
        // return new EventResource($event->load('organisateur'));
        return new EventResource($event);

    }

    public function update(Request $request, Event $event)
    {
        // $this->authorize('update', $event); // uniquement l'organisateur 
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after_or_equal:date_debut',
            'lieu' => 'sometimes|string|max:255',
            'statut' => 'sometimes|in:brouillon,publié,annulé',            
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:6144',
            'affiche' => 'image|mimes:jpg,jpeg,png|max:6144',
        ]);
        if ($request->hasFile('affiche')) {
            $affichePath = $request->file('affiche')->store('events/affiches', 'public');
            // $event->update(['affiche' => $affichePath]);
            $validated['affiche'] = $affichePath;
        }
        
        if ($request->hasFile('photos')) {
            $event->photos()->delete();
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('events/photos', 'public');
                $event->photos()->create(['image_path' => $photoPath]);
            }
        }
            dd($validated);
        $event->update($validated);
        // return new EventResource($event->load('photos','organisateur'));
        return new EventResource($event->load('photos'));

    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Événement supprimé avec succès.']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'message' => 'Veuillez fournir un terme de recherche.'
            ], 400);
        }

        // Recherche d'événements
        $events = Event::where('title', 'like', "%$query%")
            ->orWhere('address', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->with('organizer') // pour retourner l'organisateur avec l'événement
            ->get();

        // Recherche d'organisateurs (supposons que role = 'organisateur')
        $organizers = Utilisateur::where('role', 'organisateur')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%");
            })
            ->get();

        return response()->json([
            'events' => $events,
            'organizers' => $organizers,
        ]);
    }

}
