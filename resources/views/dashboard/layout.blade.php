<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EventRush Test') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Si tu utilises Vite --}}
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="{{ url('images/mail_logo.jpg') }}" class="logo rounded-100x100 shadow hover:shadow-lg transition-all" alt="EventRush Logo" style="height: 30px">
                <a href="{{ route('testdashboard') }}" class="text-2xl font-bold text-indigo-600">
                    {{ config('app.name', 'Événementiel') }}
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">{{ Auth::user()->nom ?? 'Utilisateur' }}</span>
                <form method="POST" action="{{ route('testLogout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline">Déconnexion</button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <div class="flex flex-1">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r hidden md:block">
            <nav class="p-6 space-y-4">
                <a href="{{ route('testdashboard_2') }}" class="block text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
                <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">Mes Favoris</a>
                <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">Organisateurs suivis</a>
                <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">Événements</a>
                <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">Commentaires</a>
                <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">Paramètres</a>
            </nav>
        </aside>

        {{-- Page Content --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>

    {{-- Footer --}}
    <footer class="bg-white text-center py-4 mt-8 shadow-inner">
        <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ config('app.name', 'EventRush Test') }}. Tous droits réservés.</p>
    </footer>

</body>
</html>
