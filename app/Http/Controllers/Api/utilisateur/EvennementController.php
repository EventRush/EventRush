<?php

namespace App\Http\Controllers\Api\utilisateur;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EventResource;
use App\Models\Billet;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EvennementController extends Controller
{
    //
    public function getNearbyEventsWithDate(Request $request) 
    { 
        $request->validate([ 
        'latitude' => 'required|numeric|between:-90,90', 
        'longitude' => 'required|numeric|between:-180,180', 
        'distance' => 'nullable|numeric|min:0', // km 
        'start_date' => 'nullable|date', // ex: vendredi 
        'end_date' => 'nullable|date|after_or_equal:start_date', // ex: dimanche 
        ]); 
        $latitude = $request->latitude; 
        $longitude = $request->longitude; 
        $distance = $request->distance ?? 10; 
        $query = Event::selectRaw("*, 
        (6371 * acos(cos(radians(?)) * 
        cos(radians(latitude)) * 
        cos(radians(longitude) - 
        radians(?)) + 
        sin(radians(?)) * 
        sin(radians(latitude)))) AS distance", 
        [ $latitude, $longitude, $latitude ]) 
                ->having("distance", "<=", $distance) 
                ->orderBy("distance", 'asc'); 
        // Ajouter filtre temporel (facultatif) 
        if ($request->filled('start_date') && $request->filled('end_date'))
         { 
        $query->whereBetween('date_debut', [ 
            Carbon::parse($request->start_date)->startOfDay(), 
            Carbon::parse($request->end_date)->endOfDay() 
        ]); 
        } else { // par défaut : seulement les événements futurs 
        $query->where('date_debut', '>=', now());
         } 
        $events = $query->get(); 
        return EventResource::collection($events); 
    }

    public function getTicketData($billetId)
    {
        $billet = Billet::with(['event'])->findOrFail($billetId);

        return response()->json([
            'image' => $billet->image, // image Cloudinary
            'qr_code' => $billet->qr_code,
            'event' => $billet->event->titre,
            // 'type_ticket' => $billet->ticket->type,
            // 'montant' => $billet->montant,
            // 'reference' => $billet->reference,
        ]);
    }
}
