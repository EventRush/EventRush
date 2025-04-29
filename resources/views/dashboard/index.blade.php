@extends('dashboard.layout')

@section('content')
@php
    use Carbon\Carbon;
@endphp
<div class="p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-center">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Notifications --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Notifications</h2>
            <ul class="space-y-2">
                @forelse ($notifications as $notif)
                    <li class="p-3 bg-gray-50 rounded">
                        {{ $notif->data['message'] ?? 'Notification' }}
                        <div class="text-sm text-gray-500">{{ $notif->created_at->diffForHumans() }}</div>
                    </li>
                @empty
                    <li>Aucune notification récente.</li>
                @endforelse
            </ul>
        </div>

        {{-- Favoris --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Mes Favoris</h2>
            <ul class="space-y-2">
                @forelse ($favoris as $favori)
                    <li class="p-3 bg-gray-50 rounded">
                        {{ $favori->event->titre ?? 'Événement supprimé' }}
                    </li>
                @empty
                    <li>Pas d'événements en favoris.</li>
                @endforelse
            </ul>
        </div>

        {{-- Suivis --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Organisateurs suivis</h2>
            <ul class="space-y-2">
                @forelse ($suivis as $suivi)
                    <li class="p-3 bg-gray-50 rounded">
                        {{ $suivi->organisateur->nom_entreprise ?? 'Organisateur inconnu' }}
                    </li>
                @empty
                    <li>Aucun organisateur suivi.</li>
                @endforelse
            </ul>
        </div>

        {{-- Commentaires --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Mes Commentaires</h2>
            <ul class="space-y-2">
                @forelse ($commentaires as $com)
                    <li class="p-3 bg-gray-50 rounded">
                        {{ Str::limit($com->contenu, 50) }}
                        <div class="text-sm text-gray-500">{{ $com->created_at->diffForHumans() }}</div>
                    </li>
                @empty
                    <li>Pas encore de commentaire.</li>
                @endforelse
            </ul>
        </div>

        {{-- Evénements créés (pour organisateurs) --}}
        @if (auth()->user()->role === 'organisateur')
        <div class="col-span-1 md:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Mes Événements</h2>
            <ul class="space-y-2">
                @forelse ($evenements as $event)
                    <li class="p-3 bg-gray-50 rounded">
                        <strong>{{ $event->titre }}</strong> - {{ Carbon::parse($event->date_debut)->format('d/m/Y') }}
                    </li>
                    @empty
                    <li>Vous n'avez pas encore créé d'événements.</li>
                @endforelse
            </ul>
        </div>
        @endif

    </div>
</div>
@endsection