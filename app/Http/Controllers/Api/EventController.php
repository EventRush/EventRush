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
        $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:255',
            'statut' => 'nullable|in:brouillon,publié,annulé',            
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
            'affiche' => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        ]);
        if ($request->hasFile('affiche')) {
            $affichePath = $request->file('affiche')->store('events/affiches', 'public');
            // $event->update(['affiche' => $affichePath]);
            // $validated['affiche'] = $affichePath;
            $event->affiche = $affichePath;
        }
        
        if ($request->hasFile('photos')) {
            $event->photos()->delete();
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('events/photos', 'public');
                $event->photos()->create(['image_path' => $photoPath]);
            }
        }

        if ($request->has('titre')) $event->titre = $request->titre;
        if ($request->has('description')) $event->description = $request->description;
        if ($request->has('date_debut')) $event->date_debut = $request->date_debut;
        if ($request->has('date_fin')) $event->date_fin = $request->date_fin;
        if ($request->has('lieu')) $event->lieu = $request->lieu;
        if ($request->has('statut')) $event->statut = $request->statut;
        dd($event);
        $event->save();
        // $event->affiche = $request->affiche;
        // dd($validated);
        // $event->update($validated);
        // return new EventResource($event->load('photos','organisateur'));
        return new EventResource($event->load('photos'));

    }

    public function destroy(Event $event)
    {
        $event->delete();
        $event->photos()->delete();
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
        $events = Event::where('titre', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->orWhere('lieu', 'like', "%$query%")
            // ->with('organisateur') // pour retourner l'organisateur avec l'événement
            ->get();

        // Recherche d'organisateurs (supposons que role = 'organisateur')
        $organizers = Utilisateur::where('role', 'organisateur')
            ->where(function($q) use ($query) {
                $q->where('nom', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%");
            })
            ->get();

        return response()->json([
            'events' => $events,
            'organisateurs' => $organizers,
        ]);
    }

}
