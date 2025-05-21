<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TicketRessource;
use App\Models\Event;
use App\Models\OrganisateurProfile;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisateurTicketsController extends Controller
{
    //
      public function index()
    {
         // Récupérer l'organisateur connecté
    $user = Auth::user();

    if ($user->role !== 'organisateur') {
        return response()->json([
            'message' => 'Organisateur non trouvé.'
        ], 404);
    }

    // Récupérer les tickets liés aux événements de cet organisateur
    $tickets = Ticket::whereHas('event', function ($query) use ($user) {
        $query->where('utilisateur_id', $user->id);
    })->get();

    return response()->json([
        'message' => 'Liste des tickets récupérée avec succès',
        'tickets' => TicketRessource::collection($tickets)
    ],200);

        // Récupérer l'organisateur connecté
        // $user = Auth::user();

        // if ($user->role !== 'organisateur') {
        //     return response()->json([
        //         'message' => 'Organisateur non trouvé.'
        //     ], 404);
        // }

        // // Récupérer les tickets liés aux événements de cet organisateur
        // $tickets = Ticket::whereHas('event', function ($query) use ($user) {
        //     $query->where('utilisateur_id', $user->id);
        // })->get();

        // return response()->json([
        //     'message' => 'Liste des tickets récupérée avec succès',
        //     'tickets' => $tickets
        // ],200);
    }



    public function indexTicketsEvent($eventId){
        $event = Event::findOrFail($eventId);
        $organisateur = OrganisateurProfile::where('utilisateur_id', $event->utilisateur_id)->first();
        $tickets = $event->tickets()->get();
        $tickets->transform(function($ticket){
            $ticket->image_url = $ticket->image ? asset('storage/app/public/' . $ticket->image) : null;
            return $ticket;
        });
        
        return response()->json([
            'message' => 'Tickets de l\'évènement',
            'tickets' => $tickets,
            'organisateur' => $organisateur

        ]);

    }
    public function addTicket(Request $request, $eventId){

        $organisateur = Auth::user();
        $event = Event::findOrFail($eventId);


        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        $request->validate([
            'type' => 'required|in:standart,vip1,vip2',
            'prix' => 'required|numeric' ,
            'quantite' => 'required|integer|min:1' ,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096' ,
            'date_limite_vente' => 'required|date|after:now' 

        ]);
        $imagePath = null;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('events/tickets', 'public');
        }

        $ticket = Ticket::create([
            'event_id' => $eventId,
            'type' => $request->type,
            'prix' => $request->prix,
            'quantité_disponible' => $request->quantite,
            'quantite_restante' => $request->quantite, //initier
            'image' => $imagePath,
            'date_limite_vente' => $request->date_limite_vente 
        ]);

        return response()->json($ticket, 201);
    }

    public function showTicket($ticketId){
        $ticket = Ticket::findOrFail($ticketId);
        $image = $ticket->image ? asset('storage/app/public/' . $ticket->image) : null;

        return response()->json([$ticket, $image]);
    }

    public function updateTicket(Request $request, $id){

        $organisateur = auth()->user();
        $ticket = Ticket::findOrFail($id);
        $event = Event::findOrFail($ticket->event_id);


        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'type' => 'in:standart,vip1,vip2',
            'prix' => 'nullable|numeric' ,
            'quantite_disponible' => 'nullable|integer' ,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'          
        ]);
        $imagePath = null;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('events/tickets', 'public');
            $ticket->image = $imagePath;
        }


        if ($request->has('type')) $ticket->type = $request->type;
        if ($request->has('prix')) $ticket->prix = $request->prix;
        if ($request->has('quantite_disponible')) {

            $ticket->quantité_disponible = $request->quantite_disponible;
            $ticket->quantite_restante = $request->quantite_disponible;
        }
        $ticket->save();

        return response()->json($ticket, 201);
    }

    public function destroyTicket($id){
        $ticket = Ticket::findOrFail($id);
        $organisateur = auth()->user();
        $event = Event::findOrFail($ticket->event_id);


        if ($event->utilisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $ticket->delete();
        return response()->json(['message' => "Ticket de l\'évènement - {$event->titre} - supprimé avec succès."]);
        
    }

}
