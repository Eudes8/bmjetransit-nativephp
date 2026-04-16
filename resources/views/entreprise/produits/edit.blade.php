@extends('layouts.app')

@section('title', 'Modifier - ' . $produit->nom)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('espace.produits.index') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Mes produits</a>
    </div>

    <div class="bg-white rounded-xl border p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Modifier : {{ $produit->nom }}</h2>

        <form method="POST" action="{{ route('espace.produits.update', $produit) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit</label>
                <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">{{ old('description', $produit->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categorie</label>
                <select name="categorie_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $produit->categorie_id == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix (XOF)</label>
                    <input type="number" name="prix" value="{{ old('prix', $produit->prix) }}" required min="100"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix promo</label>
                    <input type="number" name="prix_promo" value="{{ old('prix_promo', $produit->prix_promo) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $produit->stock) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poids (kg)</label>
                    <input type="number" name="poids_kg" value="{{ old('poids_kg', $produit->poids_kg) }}" min="0" step="0.1"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="actif" {{ $produit->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $produit->statut === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="en_rupture" {{ $produit->statut === 'en_rupture' ? 'selected' : '' }}>En rupture</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="en_promo" value="1" {{ $produit->en_promo ? 'checked' : '' }}
                           class="rounded border-gray-300 text-bmje-600">
                    <span class="text-sm text-gray-700">En promotion</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="est_fragile" value="1" {{ $produit->est_fragile ? 'checked' : '' }}
                           class="rounded border-gray-300 text-bmje-600">
                    <span class="text-sm text-gray-700">Fragile</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-bmje-600 text-white py-2.5 rounded-lg font-medium hover:bg-bmje-700 transition">
                    Enregistrer
                </button>
                <a href="{{ route('espace.produits.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
