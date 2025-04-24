{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        {{-- @include('admin.partials.sidebar') --}}

        {{-- Main Content --}}
        {{-- <div class="flex-1 flex flex-col"> --}}
            {{-- Header --}}
            {{-- @include('admin.partials.header') --}}

            {{-- Page Content --}}
            {{-- <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html> --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-red-200 p-6">
            <h2 class="text-xl font-bold mb-4">Sidebar test</h2>
            <ul class="space-y-2">
                <li><a href="#" class="block text-gray-700 hover:underline">Événements</a></li>
                <li><a href="#" class="block text-gray-700 hover:underline">Utilisateurs</a></li>
                <li><a href="#" class="block text-gray-700 hover:underline">Billets</a></li>
            </ul>
        </aside>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
body{
    background-color: red;
}
</body>
</html>