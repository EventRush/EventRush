<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    //
    public function index()
    {
        return response()->json(Badge::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:191',
            'icone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $badge = Badge::create($data);
        return response()->json($badge, 201);
    }

    public function show($id)
    {
        return response()->json(Badge::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:191',
            'icone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $badge->update($data);

        return response()->json($badge);
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(['status' => 'deleted']);
    }

    /**
     * Give a badge to a user
     * POST /users/{user}/badges
     * Body: badge_id
     */
    public function give(Request $request, $userId)
    {
        $data = $request->validate([
            'badge_id' => 'required|integer|exists:badges,id',
        ]);

        $user = Utilisateur::findOrFail($userId);

        $user->badges()->syncWithoutDetaching([$data['badge_id']]);

        return response()->json(['status' => 'badge_given']);
    }

    /**
     * Remove a badge
     * DELETE /users/{user}/badges/{badgeId}
     */
    public function revoke($userId, $badgeId)
    {
        $user = Utilisateur::findOrFail($userId);
        $user->badges()->detach($badgeId);

        return response()->json(['status' => 'badge_removed']);
    }

    /**
     * List badges for a user
     */
    public function indexUser($userId)
    {
        $user = Utilisateur::with('badges')->findOrFail($userId);
        return response()->json($user->badges);
    }
}
