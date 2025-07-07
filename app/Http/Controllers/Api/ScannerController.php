<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventScanneur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ScannerController extends Controller
{
    //

     public function generateScanneurs(Request $request) { 
        $request->validate([ 
            'event_id' => 'required|exists:events,id', 
            'nombre' => 'required|integer|min:1' ]);

$organisateur = Auth::user();
    $event = Event::findOrFail($request->event_id);

    // $limite = $organisateur->abonnement?->scanneur_limit ?? 5;
    $souscription = $organisateur->souscriptionActive();

    if(!$souscription) return;

        $plan = $souscription->plan;


        $pointPlan = [1 => 3,2 => 7,3 => 12];
        $limite = $pointPlan[$plan->id] ?? 0; 

    $existants = EventScanneur::where('event_id', $event->id)->count();
    $reste = $limite - $existants;

    if ($request->nombre > $reste) {
        return response()->json(['error' => "Vous ne pouvez créer que $reste scanneur(s)."], 403);
    }

    $created = [];
    for ($i = 0; $i < $request->nombre; $i++) {
        $username = strtoupper(substr($organisateur->name, 0, 3)) .
                    strtoupper(substr($event->titre, 0, 3)) .
                    str_pad($existants + $i, 3, '0', STR_PAD_LEFT);

        $password = Str::random(8);

        $user = Utilisateur::create([
            'name' => $username,
            'email' => $username . '@scan.local',
            'password' => Hash::make($password),
            'role' => 'scanneur',
        ]);

        EventScanneur::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $created[] = [
            'username' => $username,
            'password' => $password,
        ];
    }

    return response()->json(['comptes' => $created]);
}
     public function updateScanneurs(Request $request) { 
        $request->validate([ 
            'password' => 'required|exists:events,id',
         ]);

$organisateur = Auth::user();
    $event = Event::findOrFail($request->event_id);

    // $limite = $organisateur->abonnement?->scanneur_limit ?? 5;
    $souscription = $organisateur->souscriptionActive();

    if(!$souscription) return;

        $plan = $souscription->plan;


        $pointPlan = [1 => 3,2 => 7,3 => 12];
        $limite = $pointPlan[$plan->id] ?? 0; 

    $existants = EventScanneur::where('event_id', $event->id)->count();
    $reste = $limite - $existants;

    if ($request->nombre > $reste) {
        return response()->json(['error' => "Vous ne pouvez créer que $reste scanneur(s)."], 403);
    }

    $created = [];
    for ($i = 0; $i < $request->nombre; $i++) {
        $username = strtoupper(substr($organisateur->name, 0, 3)) .
                    strtoupper(substr($event->titre, 0, 3)) .
                    str_pad($existants + $i, 3, '0', STR_PAD_LEFT);

        $password = Str::random(8);

        $user = Utilisateur::create([
            'name' => $username,
            'email' => $username . '@scan.local',
            'password' => Hash::make($password),
            'role' => 'scanneur',
        ]);

        EventScanneur::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $created[] = [
            'username' => $username,
            'password' => $password,
        ];
    }

    return response()->json(['comptes' => $created]);
}

}
