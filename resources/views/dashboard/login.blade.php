@extends('dashboard.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-indigo-600 mb-6">Connexion</h2>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('testLogin') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    value="{{ old('email') }}">
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded text-indigo-600">
                    Se souvenir de moi
                </label>
                {{-- <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">Mot de passe oublié ?</a> --}}
                <a href="" class="text-indigo-600 hover:underline">Mot de passe oublié ?</a>

            </div>

            <button type="submit"
                class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-300">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Pas encore de compte ?
            {{-- <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">S'inscrire</a> --}}
                    <a href="" class="text-indigo-600 hover:underline">S'inscrire</a>

        </p>
    </div>
</div>
@endsection