<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrganisateurProfile;
use App\Models\Ticket;
use Illuminate\Http\Request;

class OrganisateurTicketsController extends Controller
{
    //
    public function indexTicketsEvent($eventsId){
        $event = Event::findOrFail($eventsId);
        $organisateur = OrganisateurProfile::where('utilisateur_id', $event->utilisateur_id)->first();
        $tickets = $event->tickets();
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
    public function addTicket(Request $request, $eventsId){

        $organisateur = auth()->user();
        $event = Event::findOrFail($eventsId);


        if ($event->organisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        $request->validate([
            'type' => 'required|in:brouillon,publié,annulé',
            'prix' => 'required|numeric' ,
            'quantite' => 'required|integer' ,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'          
        ]);
        $imagePath = null;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('events/tickets', 'public');
        }

        $ticket = Ticket::create([
            'event_id' => $eventsId,
            'type' => $request->type,
            'quantite_disponible' => $request->quantite,
            'image' => $imagePath,
        ]);

        return response()->json($ticket, 201);
    }

    public function showTicket($id){
        $ticket = Ticket::findOrFail($id);
        $image = $ticket->image ? asset('storage/app/public/' . $ticket->image) : null;

        return response()->json([$ticket, $image]);
    }

    public function updateTicket(Request $request, $id){

        $organisateur = auth()->user();
        $ticket = Ticket::findOrFail($id);
        $event = Event::findOrFail($id);


        if ($event->organisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'type' => 'in:brouillon,publié,annulé',
            'prix' => 'numeric' ,
            'quantite_disponible' => 'integer' ,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'          
        ]);
        $imagePath = null;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('events/tickets', 'public');
            $ticket->image = $imagePath;
        }

        $ticket->update($request->only([
            'type',
            'quantite_disponible',
            'image'
        ]));
        $ticket->save();

        return response()->json($ticket, 201);
    }

    public function destroyTicket($id){
        $ticket = Ticket::findOrFail($id);
        $organisateur = auth()->user();
        $event = Event::findOrFail($ticket->event_id);


        if ($event->organisateur_id !== $organisateur->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $ticket->delete();
        return response()->json(['message' => "Ticket de l\'évènement - {$event->titre} - supprimé avec succès."]);
        
    }

}
