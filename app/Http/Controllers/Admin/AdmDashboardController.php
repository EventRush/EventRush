<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AdmDashboardController extends Controller
{
    //
    public function giveBadge(Request $request, $userId)
    {
        $data = $request->validate([
            'badge_id' => 'required|integer',
        ]);

        $user = Utilisateur::findOrFail($userId);

        $user->badges()->syncWithoutDetaching([$data['badge_id']]);

        return response()->json(['status' => 'badge_added']);
    }
    

}
