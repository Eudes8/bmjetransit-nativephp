@extends('layouts.admin')

@section('title', 'Ajouter un livreur')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.livreurs') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Retour</a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border p-6">
            <form method="POST" action="{{ route('admin.livreurs.store') }}" class="space-y-6">
                @csrf

                <fieldset class="border border-gray-200 rounded-lg p-5">
                    <legend class="text-sm font-semibold text-gray-700 px-2">Compte utilisateur</legend>
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
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Mot de passe</label>
                            <input type="password" name="password" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border border-gray-200 rounded-lg p-5">
                    <legend class="text-sm font-semibold text-gray-700 px-2">Informations livreur</legend>
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Type de vehicule</label>
                            <select name="type_vehicule" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="moto" {{ old('type_vehicule') === 'moto' ? 'selected' : '' }}>Moto</option>
                                <option value="velo" {{ old('type_vehicule') === 'velo' ? 'selected' : '' }}>Velo</option>
                                <option value="voiture" {{ old('type_vehicule') === 'voiture' ? 'selected' : '' }}>Voiture</option>
                                <option value="camionnette" {{ old('type_vehicule') === 'camionnette' ? 'selected' : '' }}>Camionnette</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">N. permis (optionnel)</label>
                            <input type="text" name="numero_permis" value="{{ old('numero_permis') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Zone d'activite</label>
                            <input type="text" name="zone_activite" value="{{ old('zone_activite', 'Abidjan') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="bg-bmje-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-bmje-700 transition">
                    Creer le livreur
                </button>
            </form>
        </div>
    </div>
@endsection
