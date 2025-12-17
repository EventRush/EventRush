<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Notifications\NouvCommentaireEvent;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentaireController extends Controller
{
    //
    public function index($eventId)
    {
        $commentaires = Commentaire::where('event_id', $eventId)->with('utilisateur')->latest()->get();
        return response()->json($commentaires);
    }

    // public function store(Request $request, $eventId)
    // {
    //     $request->validate([
    //         'contenu' => 'nullable|string',
    //         'note' => 'nullable|integer|min:1|max:5',
    //     ]);

    //     $utilisateur = Auth::user();

    //     // Chercher un ancien commentaire avec une note
    //     $ancienCommentaireAvecNote = Commentaire::where('utilisateur_id', $utilisateur->id)
    //                                             ->where('event_id', $eventId)
    //                                             ->whereNotNull('note')
    //                                             ->first();

    //     $oldnote = 0;
    //     $commentaire = null;
    //     if ($ancienCommentaireAvecNote) {
    //         // Si il y a un ancien commentaire avec une note, on récupère sa note
    //         $oldnote = $ancienCommentaireAvecNote->note;
    //         // Mettre à jour la note précédente :
    //         $ancienCommentaireAvecNote->update(['note' => $request->note]);

    //         if ($request->has('contenu')) {
    //         $commentaire = Commentaire::create([
    //             'utilisateur_id' => $utilisateur->id,
    //             'event_id' => $eventId,
    //             'contenu' => $request->contenu,
    //         ]);
    //     }
    //     }  else {
    //         // Sinon tu crées un nouveau commentaire avec note
    //         $commentaire = Commentaire::create([
    //             'utilisateur_id' => $utilisateur->id,
    //             'event_id' => $eventId,
    //             'contenu' => $request->contenu,
    //             'note' => $request->note,
    //         ]);
    //     }
    //         // dd($commentaire );
    //     // Notifier l'organisateur
    // if ($commentaire) {
    //         $event = $commentaire->event;
            
    //         // dd($event );
    
    // if ($event && $event->utilisateur) {
    //     $event->utilisateur->notify(new NouvCommentaireEvent($commentaire));
    
         
    // }
    // PointService::ajouterNoteEvenement($utilisateur, $event, $oldnote);

    //             // dd($utilisateur );


    //     return response()->json([
    //         'message' => 'Commentaire ajouté avec succès.',
    //         'commentaire' => $commentaire
    //     ], 201);

    // } else {
    // return response()->json([
    //     'message' => 'Aucun commentaire n\'a été créé.',
    // ], 400);
    // }

    
    // }

    public function store(Request $request, $type, $id)
{
    $request->validate([
        'contenu' => 'nullable|string',
        'note'    => 'nullable|integer|min:1|max:5',
    ]);

    $utilisateur = Auth::user();

    // Déterminer dynamiquement le modèle cible
    $commentableClass = match ($type) {
        'event' => \App\Models\Event::class,
        'post'  => \App\Models\EventPost::class,
        default => null,
    };

    if (!$commentableClass) {
        return response()->json(['message' => 'Type de commentaire non supporté.'], 400);
    }

    $commentable = $commentableClass::findOrFail($id);

    // Vérifier si un ancien commentaire avec note existe
    $ancienCommentaireAvecNote = Commentaire::where('utilisateur_id', $utilisateur->id)
        ->where('commentable_id', $commentable->id)
        ->where('commentable_type', $commentableClass)
        ->whereNotNull('note')
        ->first();

    $oldnote = 0;
    $commentaire = null;

    if ($ancienCommentaireAvecNote) {
        $oldnote = $ancienCommentaireAvecNote->note;
        $ancienCommentaireAvecNote->update(['note' => $request->note]);

        if ($request->has('contenu')) {
            $commentaire = Commentaire::create([
                'utilisateur_id'   => $utilisateur->id,
                'commentable_id'   => $commentable->id,
                'commentable_type' => $commentableClass,
                'contenu'          => $request->contenu,
            ]);
        }
    } else {
        $commentaire = Commentaire::create([
            'utilisateur_id'   => $utilisateur->id,
            'commentable_id'   => $commentable->id,
            'commentable_type' => $commentableClass,
            'contenu'          => $request->contenu,
            'note'             => $request->note,
        ]);
    }

    

    // Points si c'est un Event
    if ($type === 'event' && $commentable instanceof \App\Models\Event) {
        // $event = $commentable;
        // if ($commentable instanceof \App\Models\Event) {
        // Notification si le commentable a un utilisateur
        if ($commentaire && method_exists($commentable, 'utilisateur') && $commentable->utilisateur) {
            // echo "\ncomentable : $commentable ";

            // echo "\ncomentaire : $commentaire->event ";
            Log::info("\ncomentable : $commentable ");
            Log::info("\ncomentaire : $commentaire->event ");
            $commentable->utilisateur->notify(new NouvCommentaireEvent($commentaire));
        }
        PointService::ajouterNoteEvenement($utilisateur, $commentable, $oldnote);
        // }
    } 
    

    return response()->json([
        'message'     => 'Commentaire ajouté avec succès.',
        'commentaire' => $commentaire
    ], 201);
}



    // public function update(Request $request, $commentId)
    // {

    //     $request->validate([
    //         'contenu' => 'nullable|string',
    //         'note' => 'nullable|integer|min:1|max:5',
    //     ]);
    //     $commentaire = Commentaire::findOrFail($commentId);

    //     if (Auth::id() !== $commentaire->utilisateur_id) {
    //         return response()->json(['message' => 'Non autorisé.'], 403);
    //     }

    //     if ($commentaire->created_at->addHour() <= now()) {
    //         return response()->json(['message' => 'Délai expiré : plus de 1h après la création.'], 403);
    //     }
    //     // dd($request);

    //     if ($request->has('contenu')) $commentaire->contenu = $request->contenu;
    //         if ($request->has('note')) $commentaire->note = $request->note;
        
    //         $commentaire->save();
        
    // // Notifier l'organisateur
    //     $event = $commentaire->event; // Relation event dans Commentaire
    //     if ($event && $event->utilisateur) {
    //         $event->utilisateur->notify(new NouvCommentaireEvent($commentaire));
    //     }

    //     return response()->json([
    //         'message' => 'Commentaire modifié avec succès.',
    //         'commentaire' => $commentaire
    //     ], 200); // 200 au lieu de 201 ici pour une mise à jour
    // }


    public function update(Request $request, $commentId)
    {
        $request->validate([
            'contenu' => 'nullable|string',
            'note'    => 'nullable|integer|min:1|max:5',
        ]);

        $commentaire = Commentaire::findOrFail($commentId);

        if (Auth::id() !== $commentaire->utilisateur_id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($commentaire->created_at->addHour() <= now()) {
            return response()->json(['message' => 'Délai expiré : plus de 1h après la création.'], 403);
        }

        if ($request->has('contenu')) $commentaire->contenu = $request->contenu;
        if ($request->has('note'))    $commentaire->note    = $request->note;

        $commentaire->save();

        // Récupérer le modèle lié (Event, Post, etc.)
        $commentable = $commentaire->commentable;

        if ($commentable && method_exists($commentable, 'utilisateur') && $commentable->utilisateur) {
            $commentable->utilisateur->notify(new NouvCommentaireEvent($commentaire));
        }

        return response()->json([
            'message'     => 'Commentaire modifié avec succès.',
            'commentaire' => $commentaire
        ], 200);
    }


    public function destroy($commentId)
    {
        $commentaire = Commentaire::findOrFail($commentId);
       
        if (Auth::id() !== $commentaire->utilisateur_id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}
