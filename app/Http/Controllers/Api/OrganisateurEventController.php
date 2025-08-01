<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use App\Models\Utilisateur;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisateurEventController extends Controller
{
    //
    public function index()
    {
        $organisateur = auth()->user();
        $events = Event::where('utilisateur_id', $organisateur->id)->latest()->get();
        return EventResource::collection($events);
    }

    public function indexEventOrgaID($organisateurId)
    {
        $organisateur = Utilisateur::find($organisateurId);
        $events = Event::where('utilisateur_id', $organisateur->id)->latest()->get();
        return EventResource::collection($events);
    }

    public function store(Request $request)
    {
        // $organisateur = auth()->user();
        $organisateur = Auth::user();

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'statut' => 'in:brouillon,publié,annulé',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:6300',
            'affiche' => 'image|mimes:jpg,jpeg,png|max:6300',
        ]);

        // Valeur par défaut pour le statut si non présent dans la requête
        if (!$request->has('statut')) {
            $validated['statut'] = 'publié';
        }

        $validated['utilisateur_id'] = $organisateur->id;

        if ($request->hasFile('affiche')) {
            
            $validated['affiche'] = Cloudinary::upload($request->file('affiche')->getRealPath())->getSecurePath();   
        }

        $event = Event::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = Cloudinary::upload($photo->getRealPath())->getSecurePath();
                // $photo->store('events/photos', 'public');
                $event->photos()->create(['image_path' => $photoPath]);
            }
        }

        
        return new EventResource($event->load('photos'));
    }

    public function show($eventId)
    {
        $event = Event::find($eventId);
        $organisateur = auth()->user();

        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return new EventResource($event);
    }

    public function update(Request $request, $eventId)
    {
        $organisateur = auth()->user();

        $event = Event::find($eventId);

        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'statut' => 'nullable|in:brouillon,publié,annulé',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
            'affiche' => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        ]);

        if ($request->hasFile('affiche')) {
            $event->affiche = Cloudinary::upload($request->file('affiche')->getRealPath())->getSecurePath();   

            // $event->affiche = $request->file('affiche')->store('events/affiches', 'public');
        }


        if ($request->hasFile('photos')) {
        // Cloudinary::destroy('nom_de_l\'image_sans_extention');
            $event->photos()->delete();
            foreach ($request->file('photos') as $photo) {
                $photoPath = Cloudinary::upload($photo->getRealPath())->getSecurePath();

                // $photoPath = $photo->store('events/photos', 'public');

                $event->photos()->create(['image_path' => $photoPath]); 
            }
        }

        if ($request->has('titre')) $event->titre = $request->titre;
        if ($request->has('description')) $event->description = $request->description;
        if ($request->has('date_debut')) $event->date_debut = $request->date_debut;
        if ($request->has('date_fin')) $event->date_fin = $request->date_fin; 
        if ($request->has('lieu')) $event->lieu = $request->lieu;
        if ($request->has('statut')) $event->statut = $request->statut;
       

        $event->save();

        return new EventResource($event->load('photos'));
    }

    /**
     * Summary of destroy
     * @param \App\Models\Event $event
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy($eventId)
    {
        $organisateur = Auth::user();
        $event = Event::find($eventId);

        // dd($event->utilisateur_id);

        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $event->photos()->delete();
        $event->delete();

        return response()->json(['message' => 'Événement supprimé avec succès.']);
    }


    // Liste des billets pour un événement 
    /**
     * Summary of eventBillets
     * @param mixed $event_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function eventBillets($eventId)
    {
        $organisateur = auth()->user();

        $event = Event::with('billets')->findOrFail($eventId);

        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return response()->json($event->billets);
    }

    // Liste des participants à un événement 
    /**
     * Summary of eventParticipants
     * @param mixed $event_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function eventParticipants($eventId)
    {
        $organisateur = auth()->user();

        $event = Event::with(['billets.utilisateur'])->findOrFail($eventId);

        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $participants = $event->billets->pluck('utilisateur')->unique('id')->values();

        return response()->json($participants);
    }
}
