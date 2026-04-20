@extends('layouts.app')

@section('title', 'Suivi de commande')

@section('content')
<div class="relative flex h-[calc(100vh-64px)] w-full flex-col bg-slate-50 overflow-hidden">
    {{-- Header Tracking --}}
    <div class="flex items-center bg-white/80 backdrop-blur-xl border-b border-slate-100 px-6 h-16 z-50 sticky top-0 shadow-sm">
        <a href="{{ url()->previous() }}" class="flex size-10 items-center justify-center rounded-lg hover:bg-blue-50/50 transition-all active:scale-95 text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="text-slate-900 text-lg font-bold leading-tight tracking-tight flex-1 ml-2">Suivi de commande</h2>
        <div class="flex size-10 items-center justify-center rounded-lg hover:bg-blue-50/50 transition-all text-slate-400">
            <span class="material-symbols-outlined">help</span>
        </div>
    </div>

    <div class="flex flex-col h-full flex-1 relative">
        @if(isset($livraison))
            <!-- Map Section -->
            <div class="flex-1 relative">
                <div class="absolute inset-0 bg-cover bg-center" style='background-image: url("https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&q=80&w=1500");'>
                    {{-- Overlay de recherche --}}
                    <div class="absolute top-4 left-4 right-16">
                        <form action="{{ route('tracking.rechercher') }}" method="POST" class="flex h-11 shadow-sm rounded-lg bg-white border border-slate-100 overflow-hidden">
                            @csrf
                            <div class="text-primary flex items-center justify-center px-3">
                                <span class="material-symbols-outlined">search</span>
                            </div>
                            <input name="numero" class="w-full text-slate-900 focus:outline-0 focus:ring-0 border-none bg-transparent placeholder:text-slate-400 px-2 text-sm font-medium" placeholder="TRK-XXXXXXXX" value="{{ $livraison->numero_tracking }}"/>
                        </form>
                    </div>

                    {{-- Map Controls --}}
                    <div class="absolute top-4 right-4 flex flex-col gap-3">
                        <div class="flex flex-col gap-px shadow-sm rounded-lg overflow-hidden border border-slate-100">
                            <button class="flex size-11 items-center justify-center bg-white text-slate-700 hover:bg-slate-50">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                            <button class="flex size-11 items-center justify-center bg-white text-slate-700 hover:bg-slate-50">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                        </div>
                        <button class="flex size-11 items-center justify-center rounded-lg bg-white text-primary shadow-sm hover:bg-slate-50 border border-slate-100">
                            <span class="material-symbols-outlined">near_me</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Tracking Panel -->
            <div class="bg-white rounded-t-3xl shadow-[0_-8px_30px_rgba(0,0,0,0.04)] relative z-20 border-t border-slate-50 animate-in slide-in-from-bottom duration-500">
                <div class="flex h-6 w-full items-center justify-center">
                    <div class="h-1 w-10 rounded-full bg-slate-200 mt-1"></div>
                </div>
                <div class="px-6 pb-8 pt-2 max-w-2xl mx-auto">
                    <!-- Status Badge -->
                    <div class="flex justify-center mb-5">
                        <span class="bg-blue-50 text-primary px-3 py-1 rounded-lg text-xs font-bold tracking-wider uppercase">
                            {{ str_replace('_', ' ', $livraison->statut) }}
                        </span>
                    </div>

                    <h2 class="text-slate-900 tracking-tight text-2xl font-extrabold text-center pb-6">
                        @if($livraison->date_livraison_estimee)
                            Arrivée prévue à {{ $livraison->date_livraison_estimee->format('H:i') }}
                        @else
                            Livraison en cours
                        @endif
                    </h2>

                    <!-- Delivery Progress Steps -->
                    <div class="flex justify-between items-center mb-8 px-6">
                        @php
                            $steps = ['assignee', 'enlevee', 'en_route', 'livree'];
                            $current_index = array_search($livraison->statut, $steps);
                            if ($current_index === false && $livraison->statut === 'nouvelle') $current_index = -1;
                        @endphp

                        <div class="flex flex-col items-center gap-2">
                            <div class="size-3 rounded-full {{ $current_index >= 0 ? 'bg-primary ring-4 ring-blue-100' : 'bg-slate-200' }}"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Préparé</span>
                        </div>
                        <div class="h-0.5 flex-1 {{ $current_index >= 1 ? 'bg-primary' : 'bg-slate-100' }} mx-3 rounded-full"></div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="size-6 rounded-lg {{ $current_index >= 1 && $current_index < 3 ? 'bg-primary ring-4 ring-blue-100 transition-transform scale-110' : ($current_index >= 3 ? 'bg-primary' : 'bg-slate-200') }} flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[16px]" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
                            </div>
                            <span class="text-[10px] font-bold {{ $current_index >= 1 ? 'text-primary' : 'text-slate-400' }} uppercase tracking-tighter">En route</span>
                        </div>
                        <div class="h-0.5 flex-1 {{ $current_index >= 3 ? 'bg-primary' : 'bg-slate-100' }} mx-3 rounded-full"></div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="size-3 rounded-full {{ $current_index >= 3 ? 'bg-primary ring-4 ring-blue-100' : 'bg-slate-200' }}"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Livré</span>
                        </div>
                    </div>

                    @if($livraison->livreur)
                        <!-- Driver Information Card -->
                        <div class="bg-slate-50/50 rounded-xl p-4 flex items-center justify-between border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img alt="{{ $livraison->livreur->user->nom_complet }}" class="size-14 rounded-full object-cover border-2 border-white shadow-sm" src="{{ $livraison->livreur->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($livraison->livreur->user->nom_complet) }}"/>
                                    <div class="absolute -bottom-0.5 -right-0.5 bg-white rounded-full p-0.5 shadow-sm">
                                        <div class="bg-green-500 size-3 rounded-full border-2 border-white"></div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $livraison->livreur->user->nom_complet }}</h3>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-amber-500 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                        <span class="text-xs font-semibold text-slate-500">4.9 (120+ courses)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="size-11 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-primary shadow-sm hover:bg-slate-50 active:scale-95">
                                    <span class="material-symbols-outlined">chat_bubble</span>
                                </button>
                                <a href="tel:{{ $livraison->livreur->user->telephone }}" class="size-11 rounded-lg bg-primary flex items-center justify-center text-white shadow-md hover:brightness-110 active:scale-95">
                                    <span class="material-symbols-outlined">call</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Vue par défaut quand aucune livraison n'est trouvée ou pas encore recherchée --}}
            <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">
                <div class="w-20 h-20 bg-blue-50 text-primary rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-4xl">local_shipping</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Suivez votre commande</h2>
                <p class="text-slate-500 max-w-sm mb-8">Entrez votre numéro de suivi pour voir l'avancement de votre livraison en temps réel.</p>

                <form action="{{ route('tracking.rechercher') }}" method="POST" class="w-full max-w-md">
                    @csrf
                    <div class="relative">
                        <input type="text" name="numero" required placeholder="TRK-XXXXXXXX"
                               class="w-full h-14 bg-white border border-slate-200 rounded-xl px-6 pr-32 text-lg font-bold placeholder:text-slate-300 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-primary text-white px-6 rounded-lg font-bold hover:opacity-90 active:scale-95 transition-all">
                            Suivre
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- Bottom Navigation Bar pour le client --}}
<nav class="flex justify-around items-center w-full h-20 bg-white/90 backdrop-blur-lg border-t border-slate-100 z-50 rounded-t-2xl shadow-[0_-4px_20px_rgba(0,0,0,0.03)] px-4">
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-all" href="{{ route('catalogue') }}">
        <span class="material-symbols-outlined">storefront</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Marketplace</p>
    </a>
    <a class="flex flex-col items-center justify-center text-primary scale-105 transition-transform" href="{{ route('tracking') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
        <p class="font-label text-[11px] font-bold tracking-wide uppercase mt-1">Suivi</p>
    </a>
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-all" href="{{ route('client.commandes') }}">
        <span class="material-symbols-outlined">receipt</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Commandes</p>
    </a>
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-all" href="{{ route('client.profil') }}">
        <span class="material-symbols-outlined">person</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Compte</p>
    </a>
</nav>
@endsection
