@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('espace.produits.index') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Mes produits</a>
    </div>

    <div class="bg-white rounded-xl border p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Ajouter un produit</h2>

        <form method="POST" action="{{ route('espace.produits.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categorie</label>
                <select name="categorie_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    <option value="">Choisir...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                            @if($cat->icone) <i class="fa-solid {{ $cat->icone }}"></i> @endif {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix (XOF)</label>
                    <input type="number" name="prix" value="{{ old('prix') }}" required min="100"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                    @error('prix') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix promo (optionnel)</label>
                    <input type="number" name="prix_promo" value="{{ old('prix_promo') }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock (optionnel)</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poids (kg, optionnel)</label>
                    <input type="number" name="poids_kg" value="{{ old('poids_kg') }}" min="0" step="0.1"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="est_fragile" value="1" {{ old('est_fragile') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-bmje-600">
                    <span class="text-sm text-gray-700">Produit fragile</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-bmje-600 text-white py-2.5 rounded-lg font-medium hover:bg-bmje-700 transition">
                Creer le produit
            </button>
        </form>
    </div>
</div>
@endsection
