<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
         if (!$user) {
             return response()->json(['message' => 'Utilisateur non authentifié'], 401);
         }
 
        $request->validate([
             'nom' => 'nullable|string|max:255',
             'email' => 'nullable|email|unique:utilisateurs,email,' . $user->id,
             'password'=>'nullable|string|min:6|',
             'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:6192',
         ]);
 
          if ($request->hasFile('avatar')) {
            $user->avatar = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();   

        }
         
        if ($request->has('nom')) $user->nom = $request->nom;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);

        $user->save();
 
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
