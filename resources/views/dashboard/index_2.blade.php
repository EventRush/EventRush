@extends('dashboard.layout')

@section('content')

@php
    use Carbon\Carbon;
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Nombre d'événements --}}
    <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all">
        <div class="text-sm text-gray-500">Événements publiés</div>
        <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $events_count }}</div>
    </div>

    {{-- Nombre de favoris --}}
    <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all">
        <div class="text-sm text-gray-500">Mes Favoris</div>
        <div class="mt-2 text-3xl font-bold text-green-500">{{ $favorites_count }}</div>
    </div>

    {{-- Nombre d'organisateurs suivis --}}
    <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all">
        <div class="text-sm text-gray-500">Organisateurs suivis</div>
        <div class="mt-2 text-3xl font-bold text-yellow-500">{{ $follows_count }}</div>
    </div>

    {{-- Nombre de commentaires --}}
    <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all">
        <div class="text-sm text-gray-500">Commentaires postés</div>
        <div class="mt-2 text-3xl font-bold text-pink-500">{{ $comments_count }}</div>
    </div>

    {{-- Nombre de notifications --}}
    <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-all">
        <div class="text-sm text-gray-500">Notifications non lues</div>
        <div class="mt-2 text-3xl font-bold text-orange-500">{{ $notifunread->count() }}</div>
    </div>

</div>


{{-- Section événements récents --}}
<div class="mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Derniers événements</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($recent_events as $event)
            <div class="relative rounded-2xl overflow-hidden shadow hover:shadow-lg transition-all h-60 bg-cover bg-center"
                 style="background-image: url('{{ $event->affiche }}')">
                 
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-black bg-opacity-50 p-5 flex flex-col justify-end">
                    <h3 class="text-lg font-bold text-white">{{ $event->titre }}</h3>
                    <p class="text-sm text-gray-200 mt-1">{{ Str::limit($event->description, 80) }}</p>
                    <div class="text-xs text-gray-300 mt-2">{{ Carbon::parse($event->date_debut)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-300 mt-2">{{ $event->affiche }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection