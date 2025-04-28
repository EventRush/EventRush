<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Notifications\NouvCommentaireEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    //
    public function index($eventId)
    {
        $commentaires = Commentaire::where('event_id', $eventId)->with('utilisateur')->latest()->get();
        return response()->json($commentaires);
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'contenu' => 'nullable|string',
            'note' => 'nullable|integer|min:1|max:5',
        ]);

        $commentaire = Commentaire::create([
            'event_id' => $eventId,
            'utilisateur_id' => Auth::id(),
            'contenu' => $request->contenu,
            'note' => $request->note,
        ]);

        // Notifier l'organisateur
    $event = $commentaire->event; // Relation event dans Commentaire
    if ($event && $event->utilisateur) {
        $event->utilisateur->notify(new NouvCommentaireEvent($commentaire));
    }


        return response()->json([
            'message' => 'Commentaire ajouté avec succès.',
            'commentaire' => $commentaire
        ], 201);
    }
    public function update(Request $request, $comId)
{

    $request->validate([
        'contenu' => 'nullable|string',
        'note' => 'nullable|integer|min:1|max:5',
    ]);
    $commentaire = Commentaire::findOrFail($comId);

    if (Auth::id() !== $commentaire->utilisateur_id) {
        return response()->json(['message' => 'Non autorisé.'], 403);
    }

    if ($commentaire->created_at->addHour() <= now()) {
        return response()->json(['message' => 'Délai expiré : plus de 1h après la création.'], 403);
    }
    // dd($request);

    if ($request->has('contenu')) $commentaire->contenu = $request->contenu;
        if ($request->has('note')) $commentaire->note = $request->note;
       
        $commentaire->save();
    
// Notifier l'organisateur
    $event = $commentaire->event; // Relation event dans Commentaire
    if ($event && $event->utilisateur) {
        $event->utilisateur->notify(new NouvCommentaireEvent($commentaire));
    }

    return response()->json([
        'message' => 'Commentaire modifié avec succès.',
        'commentaire' => $commentaire
    ], 200); // 200 au lieu de 201 ici pour une mise à jour
}

    public function destroy($id)
    {
        $commentaire = Commentaire::findOrFail($id);
       
        if (Auth::id() !== $commentaire->utilisateur_id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}
