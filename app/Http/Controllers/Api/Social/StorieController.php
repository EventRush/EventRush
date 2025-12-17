<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\Storie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class StorieController extends Controller
{
    //
    public function activeStories()
    {
        return Storie::where('expires_at', '>', now())
            ->latest()
            ->get();
    }
     /**
     * Store a Storie (expires_at should be set to now()->addHours(24) by default)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // 'media' => 'required|',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mpeg|max:10240',
            'expires_at' => 'nullable|date',
        ]);
        if ($request->hasFile('affiche')) {
            
            $path = Cloudinary::upload($request->file('media')->getRealPath())->getSecurePath();   
        }
        $expiresAt = $data['expires_at'] ?? now()->addHours(24);

        $Storie = Storie::create([
            'utilisateur_id' => auth()->id(),
            'media_path'     => $path,
            'expires_at'     => $expiresAt,
        ]);

        return response()->json($Storie, 201);
    }

    /**
     * List active stories (non-expired)
     */
    public function active()
    {
        $stories = Storie::with('utilisateur')
            ->where('expires_at', '>', now())
            ->latest()
            ->get();

        return response()->json($stories);
    }

    /**
     * Delete own Storie
     */
    public function destroy($id)
    {
        $Storie = Storie::findOrFail($id);

        if ($Storie->utilisateur_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $Storie->delete();

        return response()->json(['status' => 'deleted']);
    }

    /**
     * Optional: list my own stories
     */
    public function myStories()
    {
        $stories = Storie::where('utilisateur_id', auth()->id())->latest()->get();
        return response()->json($stories);
    }

}
