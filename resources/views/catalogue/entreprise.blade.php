@extends('layouts.app')

@section('title', $entreprise->raison_sociale)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- En-tete boutique --}}
    <div class="bg-white rounded-xl border p-8 mb-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-bmje-100 text-bmje-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $entreprise->raison_sociale }}</h1>
                @if($entreprise->sigle)
                    <span class="text-gray-400 text-sm">({{ $entreprise->sigle }})</span>
                @endif
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span><i class="fa-solid fa-location-dot mr-1"></i> {{ $entreprise->ville }}</span>
                    <span><i class="fa-solid fa-box mr-1"></i> {{ $entreprise->produits->count() }} produits</span>
                    @if($entreprise->note_moyenne)
                        <span><i class="fa-solid fa-star text-yellow-500 mr-1"></i> {{ number_format($entreprise->note_moyenne, 1) }}/5</span>
                    @endif
                </div>
                @if($entreprise->description)
                    <p class="text-gray-500 text-sm mt-3 max-w-xl">{{ $entreprise->description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Produits --}}
    <h2 class="text-lg font-bold text-gray-800 mb-4">Produits disponibles</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($entreprise->produits as $produit)
            <a href="{{ route('catalogue.show', $produit) }}" class="bg-white rounded-xl border overflow-hidden hover:shadow-md transition group">
                <div class="aspect-square bg-gray-100 flex items-center justify-center">
                    @if($produit->images && count($produit->images) > 0)
                        <img src="{{ $produit->images[0] }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-bmje-600">{{ $produit->nom }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-bold text-bmje-700">{{ number_format($produit->prix_actuel, 0, ',', ' ') }} XOF</span>
                        @if($produit->en_promo && $produit->prix_promo)
                            <span class="text-xs text-gray-400 line-through">{{ number_format($produit->prix, 0, ',', ' ') }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <i class="fa-solid fa-box-open text-3xl mb-3 block"></i>
                <p>Cette entreprise n'a pas encore de produit.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
