@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar filtres --}}
        <aside class="lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('catalogue') }}" class="bg-white rounded-xl border p-5 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Nom du produit..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categorie</label>
                    <select name="categorie" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Prix min</label>
                        <input type="number" name="prix_min" value="{{ request('prix_min') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Prix max</label>
                        <input type="number" name="prix_max" value="{{ request('prix_max') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                    <select name="tri" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="recent" {{ request('tri') === 'recent' ? 'selected' : '' }}>Plus recents</option>
                        <option value="prix_asc" {{ request('tri') === 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix decroissant</option>
                        <option value="populaire" {{ request('tri') === 'populaire' ? 'selected' : '' }}>Populaires</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-bmje-600 text-white py-2 rounded-lg text-sm hover:bg-bmje-700">Filtrer</button>
            </form>
        </aside>

        {{-- Grille produits --}}
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-gray-800">Catalogue</h1>
                <span class="text-sm text-gray-400">{{ $produits->total() }} produit(s)</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($produits as $produit)
                    <a href="{{ route('catalogue.show', $produit) }}" class="bg-white rounded-xl border overflow-hidden hover:shadow-md transition group">
                        <div class="aspect-square bg-gray-100 flex items-center justify-center">
                            @if($produit->images && count($produit->images) > 0)
                                <img src="{{ $produit->images[0] }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-xs text-gray-400 truncate">{{ $produit->entreprise->raison_sociale }}</p>
                            <h3 class="text-sm font-medium text-gray-800 mt-1 line-clamp-2 group-hover:text-bmje-600">{{ $produit->nom }}</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="font-bold text-bmje-700">{{ number_format($produit->prix_actuel, 0, ',', ' ') }} XOF</span>
                                @if($produit->en_promo && $produit->prix_promo)
                                    <span class="text-xs text-gray-400 line-through">{{ number_format($produit->prix, 0, ',', ' ') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400">
                        <i class="fa-solid fa-box-open text-4xl mb-4 block"></i>
                        <p>Aucun produit trouve.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $produits->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
