@extends('layouts.app')

@section('title', 'Inscription Entreprise')

@section('content')
<div class="max-w-2xl mx-auto mt-12 px-4 mb-16">
    <div class="bg-white rounded-xl shadow-sm border p-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Inscrire mon entreprise</h2>
        <p class="text-gray-500 text-center text-sm mb-8">Vendez vos produits sur BMJeTransit et profitez de notre reseau de livraison.</p>

        <form method="POST" action="{{ route('register.entreprise') }}" class="space-y-6">
            @csrf

            <fieldset class="border border-gray-200 rounded-lg p-5">
                <legend class="text-sm font-semibold text-gray-700 px-2">Informations personnelles</legend>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Prenom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Telephone</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Mot de passe</label>
                        <input type="password" name="password" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Confirmer</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border border-gray-200 rounded-lg p-5">
                <legend class="text-sm font-semibold text-gray-700 px-2">Informations entreprise</legend>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm text-gray-600 mb-1">Raison sociale</label>
                        <input type="text" name="raison_sociale" value="{{ old('raison_sociale') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sigle (optionnel)</label>
                        <input type="text" name="sigle" value="{{ old('sigle') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Secteur d'activite</label>
                        <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">N. registre commerce (optionnel)</label>
                        <input type="text" name="registre_commerce" value="{{ old('registre_commerce') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', 'Abidjan') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">Description (optionnel)</label>
                        <textarea name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="w-full bg-bmje-600 text-white py-3 rounded-lg font-medium hover:bg-bmje-700 transition">
                Soumettre ma candidature
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-4">Votre demande sera examinee par l'equipe BMJeTransit sous 24-48h.</p>
    </div>
</div>
@endsection
