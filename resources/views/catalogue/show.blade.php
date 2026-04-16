@extends('layouts.app')

@section('title', $produit->nom)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            {{-- Image --}}
            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                @if($produit->images && count($produit->images) > 0)
                    <img src="{{ $produit->images[0] }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
                @else
                    <i class="fa-solid fa-image text-gray-300 text-5xl"></i>
                @endif
            </div>

            {{-- Details --}}
            <div class="p-8">
                <a href="{{ route('catalogue.entreprise', $produit->entreprise) }}" class="text-sm text-bmje-600 hover:underline">
                    {{ $produit->entreprise->raison_sociale }}
                </a>

                <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $produit->nom }}</h1>

                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-bmje-700">{{ number_format($produit->prix_actuel, 0, ',', ' ') }} XOF</span>
                    @if($produit->en_promo && $produit->prix_promo)
                        <span class="text-lg text-gray-400 line-through">{{ number_format($produit->prix, 0, ',', ' ') }} XOF</span>
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-medium">PROMO</span>
                    @endif
                </div>

                <p class="text-sm text-gray-500 mt-1">+ 1 500 XOF de frais de livraison</p>

                @if($produit->description)
                    <div class="mt-6 text-gray-600 text-sm leading-relaxed">{{ $produit->description }}</div>
                @endif

                <div class="mt-6 space-y-2 text-sm text-gray-500">
                    @if($produit->categorie)
                        <p><i class="fa-solid fa-tag w-5 mr-1"></i> {{ $produit->categorie->nom }}</p>
                    @endif
                    @if($produit->stock !== null)
                        <p><i class="fa-solid fa-warehouse w-5 mr-1"></i> {{ $produit->stock > 0 ? $produit->stock . ' en stock' : 'Rupture de stock' }}</p>
                    @endif
                    @if($produit->poids_kg)
                        <p><i class="fa-solid fa-weight-scale w-5 mr-1"></i> {{ $produit->poids_kg }} kg</p>
                    @endif
                    @if($produit->est_fragile)
                        <p><i class="fa-solid fa-triangle-exclamation w-5 mr-1 text-orange-500"></i> Produit fragile</p>
                    @endif
                </div>

                @auth
                    <div class="mt-8">
                        <a href="#commander" class="w-full bg-bmje-600 text-white py-3 rounded-lg font-medium hover:bg-bmje-700 transition text-center block">
                            <i class="fa-solid fa-cart-shopping mr-2"></i> Commander
                        </a>
                    </div>
                @else
                    <div class="mt-8">
                        <a href="{{ route('login') }}" class="w-full bg-bmje-600 text-white py-3 rounded-lg font-medium hover:bg-bmje-700 transition text-center block">
                            Connectez-vous pour commander
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Produits similaires --}}
    @if($similaires->count() > 0)
        <div class="mt-12">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Produits similaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($similaires as $sim)
                    <a href="{{ route('catalogue.show', $sim) }}" class="bg-white rounded-xl border overflow-hidden hover:shadow-md transition">
                        <div class="aspect-square bg-gray-100 flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-800 truncate">{{ $sim->nom }}</h3>
                            <span class="font-bold text-bmje-700 text-sm">{{ number_format($sim->prix_actuel, 0, ',', ' ') }} XOF</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
