<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    //
    public function toggleReaction(Request $request)
    {
        $data = $request->validate([
            'reactable_type' => 'required|string',
            'reactable_id' => 'required|integer',
            'type' => 'nullable|string',
        ]);

        $existing = Reaction::where([
            'utilisateur_id' => auth()->id(),
            'reactable_type' => $data['reactable_type'],
            'reactable_id' => $data['reactable_id'],
        ])->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        $reaction = Reaction::create([
            'utilisateur_id' => auth()->id(),
            'type' => $data['type'] ?? 'like',
            'reactable_type' => $data['reactable_type'],
            'reactable_id' => $data['reactable_id'],
        ]);

        return response()->json(['status' => 'added', 'reaction' => $reaction]);
    }

    /**
     * Toggle a reaction on a reactable (event, post, commentaire...)
     * Body: reactable_type, reactable_id, type (optional)
     */
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'reactable_type' => 'required|string',
            'reactable_id'   => 'required|integer',
            'type'           => 'nullable|string',
        ]);

        $userId = auth()->id();

        $existing = Reaction::where([
            'utilisateur_id'   => $userId,
            'reactable_type'   => $data['reactable_type'],
            'reactable_id'     => $data['reactable_id'],
        ])->first();

        if ($existing) {
            // If incoming type matches existing type, remove (toggle off)
            if (!empty($data['type']) && $existing->type !== $data['type']) {
                // change the reaction type
                $existing->update(['type' => $data['type']]);
                return response()->json(['status' => 'updated', 'reaction' => $existing], 200);
            }

            $existing->delete();
            return response()->json(['status' => 'removed'], 200);
        }

        $reaction = Reaction::create([
            'utilisateur_id' => $userId,
            'type'           => $data['type'] ?? 'like',
            'reactable_type' => $data['reactable_type'],
            'reactable_id'   => $data['reactable_id'],
        ]);

        return response()->json(['status' => 'added', 'reaction' => $reaction], 201);
    }

    /**
     * Return reactions summary for a reactable
     * GET /reactions?reactable_type=...&reactable_id=...
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'reactable_type' => 'required|string',
            'reactable_id'   => 'required|integer',
        ]);

        $query = Reaction::where('reactable_type', $data['reactable_type'])
            ->where('reactable_id', $data['reactable_id']);

        $counts = $query->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        $userReaction = null;
        if (auth()->check()) {
            $userReaction = $query->where('utilisateur_id', auth()->id())->first();
        }

        return response()->json([
            'counts' => $counts,
            'user_reaction' => $userReaction,
        ]);
    }

}
