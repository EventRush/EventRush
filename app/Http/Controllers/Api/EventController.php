<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Event;
use App\Models\Utilisateur;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $admin = auth()->user();

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'], 403);

        }
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

            $validated['utilisateur_id'] = auth()->id();

            // Valeur par défaut pour le statut si non présent dans la requête
            if (!$request->has('statut')) {
                $validated['statut'] = 'publié';
            }
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
    
    /**
     * Summary of show
     * @param mixed $eventId
     * @return EventResource
     */

    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        $utilisateur = Auth::user();

        if ($utilisateur) {
        PointService::ajouterVueEvenement($utilisateur, $event);
    }

        // return new EventResource($event->load('organisateur'));
        return new EventResource($event);

    }

    public function update(Request $request, $eventId)
    {
        $admin = auth()->user();
        $event = Event::findOrFail($eventId);

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'], 403);

        }
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
        // dd($event);
        $event->save();
        
        // return new EventResource($event->load('photos','organisateur'));
        return new EventResource($event->load('photos'));

    }

    public function destroy(Event $event)
    {
        $admin = auth()->user();

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'], 403);

        }
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

    public function searchTire(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'message' => 'Veuillez fournir un terme de recherche.'
            ], 400);
        }

        // Recherche d'événements
        $events = Event::where('titre', 'like', "%$query%")
            // ->with('organisateur') // pour retourner l'organisateur avec l'événement
            ->get();


        return response()->json([
            'events' => $events,
        ]);
    }

    public function searchDesc(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'message' => 'Veuillez fournir un terme de recherche.'
            ], 400);
        }

        // Recherche d'événements
        $events = Event::where('description', 'like', "%$query%")
            // ->with('organisateur') // pour retourner l'organisateur avec l'événement
            ->get();


        return response()->json([
            'events' => $events,
        ]);
    }

    public function searchLieu(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'message' => 'Veuillez fournir un terme de recherche.'
            ], 400);
        }

        // Recherche d'événements
        $events = Event::where('lieu', 'like', "%$query%")
            // ->with('organisateur') // pour retourner l'organisateur avec l'événement
            ->get();


        return response()->json([
            'events' => $events,
        ]);
    }

    public function searchDate(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json([
                'message' => 'Veuillez fournir uneme de recherche.'
            ], 400);
        }

        // Recherche d'événements
        $events = Event::whereDate('date_debut', $date)
            // ->with('organisateur') // pour retourner l'organisateur avec l'événement
            ->get();


        return response()->json([
            'events' => $events,
        ]);
    }

    
     // Liste des événements filtrables
    public function search_2(Request $request)
    {
        $query = Event::query();

        // Filtre : événements à venir
        if ($request->boolean('upcoming')) {
            $query->where('date', '>=', now());
        }

        // Filtre : événements passés
        if ($request->boolean('past')) {
            $query->where('date', '<', now());
        }

        // Filtre : événements en vedette
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Recherche par mot-clé (titre, description)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Limite et pagination
        $limit = $request->input('limit', 10);

        return response()->json($query->latest()->paginate($limit));
    }

    public function featured()
    {
        $events = Event::where('is_featured', true)
            ->where('date', '>=', now())
            ->latest()
            ->take(5)
            ->get();

        return response()->json($events);
    }

    // Récupérer les événements à venir (accueil)
    public function upcoming()
    {
        $events = Event::where('date_debut', '>=', now())
            ->orderBy('date_debut')
            ->take(5)
            ->get();

        return response()->json($events);
    }


    public function popular(Request $request)
    {
    // seuil facultatif, par défaut à 50 points
    $minPoints = $request->query('min', 50);
    // $minPoints = 0;

    $events = Event::where('points', '>=', $minPoints)
        ->with('organisateur')
        ->orderByDesc('points')
        ->limit(10)
        ->get();

    return EventResource::collection($events);
    }

    // Récupérer les événements d’un organisateur (profil public)
    public function byOrganisateur($id)
    {
        $events = Event::where('organisateur_id', $id)
            ->where('date', '>=', now())
            ->latest()
            ->get();

        return response()->json($events);
    }


    // utilisateurs functions 

    public function getEventsNear(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'distance' => 'nullable|numeric|min:0', // en kilomètres
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $distance = $request->distance ?? 10; // distance par défaut = 10 km

        $events = Event::selectRaw("*,
            (6371 * acos(cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude)))) AS distance", [
                $latitude, $longitude, $latitude
            ])
            ->having("distance", "<=", $distance)
            ->orderBy("distance", 'asc')
            ->get();

        return EventResource::collection($events);
    }

    
 

}
