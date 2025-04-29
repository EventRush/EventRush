<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Event;
use App\Models\Favori;
use App\Models\Suivi;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TestContoller extends Controller
{
    //

    public function index_2()
    {
        $user = Auth::user();
        $notif_unread =$user->unreadNotifications;

        return view('dashboard.index_2', [
            'events_count' => Event::count(),
            'favorites_count' => Favori::where('utilisateur_id', $user->id)->count(),
            'follows_count' => Suivi::where('utilisateur_id', $user->id)->count(),
            'comments_count' => Commentaire::where('utilisateur_id', $user->id)->count(),
            'notifunread' => $notif_unread,
            'recent_events' => Event::latest()->take(6)->get(),
        ]);
}


    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->take(10)->get();
        $favoris = $user->favoris;
        $suivis = $user->suivis;

        $evenements = [];
        if ($user->role === 'organisateur') {
            $evenements = Event
            ::where('utilisateur_id', $user->id)->latest()->get();
        }

        $commentaires = Commentaire::where('utilisateur_id', $user->id)->latest()->take(10)->get();

        return view('dashboard.index', compact('notifications', 'favoris', 'suivis', 'evenements', 'commentaires'));
    }

    // Affiche la vue de connexion
    public function testLoginForm()
    {
        return view('dashboard.login');
    }

    // Traite la connexion
    public function testLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('status', 'Connexion réussie');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe invalide.',
        ])->onlyInput('email');
    }

    // Déconnexion
    public function testLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/testlogin')->with('status', 'Déconnecté avec succès.');
    }


}
