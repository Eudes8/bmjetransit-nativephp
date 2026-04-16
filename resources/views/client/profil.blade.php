@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mon profil</h1>

    <div class="bg-white rounded-xl border p-6 mb-6">
        <form method="POST" action="{{ route('client.profil') }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prenom</label>
                    <input type="text" name="prenom" value="{{ auth()->user()->prenom }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ auth()->user()->nom }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                <input type="text" name="telephone" value="{{ auth()->user()->telephone }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse par defaut</label>
                <input type="text" name="adresse" value="{{ auth()->user()->adresse }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="ville" value="{{ auth()->user()->ville }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <button type="submit" class="bg-bmje-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-bmje-700 transition">
                Mettre a jour
            </button>
        </form>
    </div>

    {{-- Changer mot de passe --}}
    <div class="bg-white rounded-xl border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Changer le mot de passe</h3>
        <form method="POST" action="{{ route('client.profil') }}" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="change_password" value="1">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <button type="submit" class="bg-gray-800 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-gray-900 transition">
                Changer le mot de passe
            </button>
        </form>
    </div>
</div>
@endsection
