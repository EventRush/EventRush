{{-- <aside class="w-64 bg-white border-r border-gray-200 min-h-screen hidden md:block">
    <div class="p-6">
        {{-- Logo --}}
        {{-- <div class="text-2xl font-bold text-indigo-600 mb-8">
            ConcertHub
        </div> --}}

        {{-- Navigation --}}
        {{-- <nav class="space-y-2 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
                <svg class="w-5 h-5 mr-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7m-9 2v10" /></svg>
                Dashboard
            </a> --}}
            {{-- <a href="{{ route('admin.events.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
                <svg class="w-5 h-5 mr-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" /></svg>
                Événements
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
                <svg class="w-5 h-5 mr-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 110-8 4 4 0 010 8zm6 4a4 4 0 100-8 4 4 0 000 8z" /></svg>
                Utilisateurs
            </a> --}}
            {{-- <a href="{{ route('admin.tickets.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
                <svg class="w-5 h-5 mr-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 01.553-1.382L9 2m0 18v-8m0 8h6m0 0V2m0 18l5.447-2.724A2 2 0 0021 15.382V6.618a2 2 0 00-.553-1.382L15 2" /></svg>
                Billets
            </a>
        </nav>
    </div>
</aside> --}}

<aside class="w-64 bg-red-100  border-r border-gray-200 hidden md:block">
    Hello sidebar !
    <div class="p-6 space-y-6">
        <div class="text-xl font-bold text-indigo-600">ConcertHub Admin</div>
        <nav class="space-y-2 text-sm">
            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100">
                <span class="material-symbols-outlined">event</span>
                <span>Événements</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100">
                <span class="material-symbols-outlined">person</span>
                <span>Utilisateurs</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded hover:bg-indigo-100">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span>Billets</span>
            </a>
        </nav>
    </div>
</aside>