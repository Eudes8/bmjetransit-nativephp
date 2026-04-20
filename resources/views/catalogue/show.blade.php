@extends('layouts.app')

@section('title', $produit->nom . ' - Détail du produit')

@section('content')
<header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-sm px-6 h-16 flex justify-between items-center md:hidden">
    <a href="{{ route('catalogue') }}" class="flex items-center text-slate-900 active:scale-95 transition-transform duration-150">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="text-lg font-semibold tracking-tight">Détail du produit</h1>
    <button class="flex items-center text-slate-900 active:scale-95 transition-transform duration-150">
        <span class="material-symbols-outlined">share</span>
    </button>
</header>

<main class="max-w-md mx-auto bg-white min-h-screen pt-16 pb-32 shadow-sm">
    <!-- Product Image Section -->
    <div class="aspect-square w-full bg-slate-100 relative overflow-hidden">
        @if($produit->images && count($produit->images) > 0)
            <img src="{{ $produit->images[0] }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
        @else
            <div class="h-full w-full flex items-center justify-center text-slate-300">
                <span class="material-symbols-outlined text-6xl">image</span>
            </div>
        @endif
    </div>

    <!-- Product Core Info -->
    <div class="px-6 pt-6">
        <div class="flex justify-between items-start">
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">{{ $produit->nom }}</h2>
            @if($produit->en_promo)
                <span class="bg-blue-50 text-primary text-[11px] font-bold px-2 py-1 rounded uppercase tracking-wider">PROMO</span>
            @endif
        </div>
        <p class="text-2xl font-extrabold text-primary mt-2">{{ number_format($produit->prix_actuel, 0, ',', ' ') }} XOF</p>
    </div>

    <!-- Delivery Estimation -->
    <div class="mx-6 mt-6 p-4 bg-slate-50 rounded-lg border border-slate-100 flex items-center gap-3">
        <span class="material-symbols-outlined text-primary">local_shipping</span>
        <div class="flex flex-col">
            <p class="text-sm font-bold text-slate-900">Livraison estimée : 2 500 XOF</p>
            <p class="text-xs text-slate-500">Arrivée prévue entre 24h et 48h (Abidjan)</p>
        </div>
    </div>

    <!-- Description -->
    <div class="px-6 mt-8">
        <h3 class="text-[11px] uppercase tracking-widest font-extrabold text-slate-400 mb-3">Description</h3>
        <p class="text-slate-600 leading-relaxed text-sm">
            {{ $produit->description ?? 'Aucune description disponible pour ce produit.' }}
        </p>
        <ul class="mt-4 space-y-2">
            @if($produit->est_fragile)
                <li class="flex items-center gap-2 text-sm text-slate-600">
                    <span class="material-symbols-outlined text-orange-500 text-lg" style="font-variation-settings: 'FILL' 1;">warning</span>
                    Produit fragile
                </li>
            @endif
            <li class="flex items-center gap-2 text-sm text-slate-600">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                Qualité certifiée par BMJe
            </li>
        </ul>
    </div>

    <!-- Seller Information -->
    <div class="px-6 mt-8">
        <h3 class="text-[11px] uppercase tracking-widest font-extrabold text-slate-400 mb-3">Vendu par</h3>
        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-lg bg-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-cover bg-center border-2 border-slate-50 overflow-hidden">
                    <img src="{{ $produit->entreprise->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($produit->entreprise->raison_sociale) }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-slate-900 text-sm">{{ $produit->entreprise->raison_sociale }}</p>
                    <div class="flex items-center gap-1 text-amber-500">
                        <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-[11px] font-bold text-slate-500">4.8 (120 avis)</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('catalogue.entreprise', $produit->entreprise) }}" class="text-primary font-bold text-xs hover:underline">Voir boutique</a>
        </div>
    </div>

    <!-- Sticky Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white/90 backdrop-blur-lg p-4 border-t border-slate-100 flex gap-3 items-center z-40">
        <button class="w-12 h-12 flex items-center justify-center border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 active:scale-95 transition-all">
            <span class="material-symbols-outlined">favorite</span>
        </button>
        <form action="{{ route('panier.ajouter', $produit) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full bg-primary text-white h-12 rounded-lg font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 active:scale-[0.98] transition-all">
                <span class="material-symbols-outlined text-xl">shopping_cart</span>
                Ajouter au panier
            </button>
        </form>
    </div>
</main>
@endsection
