@extends('layouts.app')

@section('title', 'Profil entreprise')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil entreprise</h1>

    @php $ent = auth()->user()->entreprise; @endphp

    <div class="bg-white rounded-xl border p-6 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-bmje-100 text-bmje-600 rounded-xl flex items-center justify-center text-2xl">
                <i class="fa-solid fa-building"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $ent->raison_sociale }}</h2>
                @if($ent->sigle) <p class="text-sm text-gray-400">{{ $ent->sigle }}</p> @endif
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $ent->statut === 'approuvee' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst(str_replace('_',' ',$ent->statut)) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Secteur</p>
                <p class="font-medium">{{ $ent->secteur_activite }}</p>
            </div>
            <div>
                <p class="text-gray-500">Commission</p>
                <p class="font-medium">{{ $ent->commission_taux }}%</p>
            </div>
            <div>
                <p class="text-gray-500">Ville</p>
                <p class="font-medium">{{ $ent->ville }}</p>
            </div>
            <div>
                <p class="text-gray-500">Adresse</p>
                <p class="font-medium">{{ $ent->adresse }}</p>
            </div>
            @if($ent->registre_commerce)
                <div>
                    <p class="text-gray-500">Registre commerce</p>
                    <p class="font-medium">{{ $ent->registre_commerce }}</p>
                </div>
            @endif
            <div>
                <p class="text-gray-500">Forfait actif</p>
                <p class="font-medium">{{ $ent->abonnementActif?->forfait?->nom ?? 'Aucun' }}</p>
            </div>
        </div>

        @if($ent->description)
            <div class="mt-4 pt-4 border-t">
                <p class="text-gray-500 text-sm mb-1">Description</p>
                <p class="text-sm text-gray-700">{{ $ent->description }}</p>
            </div>
        @endif
    </div>

    {{-- Modifier profil perso --}}
    <div class="bg-white rounded-xl border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Vos informations personnelles</h3>
        <form method="POST" action="{{ route('espace.profil') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Prenom</label>
                    <input type="text" name="prenom" value="{{ auth()->user()->prenom }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ auth()->user()->nom }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Telephone</label>
                <input type="text" name="telephone" value="{{ auth()->user()->telephone }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-bmje-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-bmje-700">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
