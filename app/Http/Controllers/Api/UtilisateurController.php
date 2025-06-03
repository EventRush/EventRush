<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    /**
     * Profile
     * 
     */
    public function me(Request $request)
{
    

    $user = Auth::user();

    return response()->json([
        'id' => $user->id,
        'nom' => $user->nom,
        'email' => $user->email,
        'avatar' => $user->avatar,
        'role' => $user->role, 
    ]);
}

// /**
//  * @param \Illuminate\Http\Request $request
//  * @return \Illuminate\Http\JsonResponse
//  */

 /**
     * Met à jour le profil de l'utilisateur connecté.
     *
     * @authenticated
     *
     * @bodyParam nom string Nom de l'utilisateur. Exemple: Doe
     * @bodyParam email string Email de l'utilisateur. Exemple: doe@example.com
     * @bodyParam avatar file Image de profil (jpg, jpeg, png, max 2 Mo)
     *
     * @response 200 {
     *  "message": "Profil mis à jour avec succès.",
     *  "user": {
     *    "id": 1,
     *    "nom": "Doe",
     *    "email": "doe@example.com",
     *    "avatar": "avatars/avatar.png"
     *  }
     * }
     */

     public function update(Request $request)
     {
         $user = Auth::user(); 
         if (!$user instanceof Utilisateur) {
             return response()->json(['message' => 'Utilisateur non authentifié'], 401);
         }
 
         $validated = $request->validate([
             'nom' => '|nullable|string|max:255',
             'email' => '|nullable|email|unique:utilisateurs,email,' ,
             'avatar' => '|nullable|image|mimes:jpg,jpeg,png|max:2048',
         ]);
 
         if ($request->hasFile('avatar')) {
             if ($user->avatar) {
                 Storage::disk('public')->delete($user->avatar);
             }
 
             $path = $request->file('avatar')->store('avatars', 'public');
             $validated['avatar'] = $path;
         }
        //  dd($validated);
        // return response()->json($validated);

        // if ($request->has('nom')) $user->nom = $request->nom;
        // if ($request->has('email')) $user->email = $request->email;
        
        //  $user->update($validated);
        $user->fill($validated)->save();
 
         return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user' => $user->fresh()
        ]);
}
    public function connectedUser(){
        $user = Auth::user();
        return response()->json([
            'message' => 'Utilisateur conecté',
            'user' => $user,
        ]);
    }

 

}
