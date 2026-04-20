@extends('layouts.app')

@section('title', 'Livreur Dashboard')

@section('content')
<main class="pt-20 px-6 max-w-lg mx-auto">
    <!-- Dashboard Intro -->
    <section class="mb-8">
        <div class="flex flex-col gap-1">
            <span class="text-[11px] font-bold text-primary uppercase tracking-widest font-label">BIENVENUE, {{ auth()->user()->prenom }}</span>
            <h2 class="text-3xl font-extrabold tracking-tight">Missions du jour</h2>
        </div>
    </section>

    <!-- Bento Grid: Gains Summary -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <!-- Earnings Card -->
        <div class="col-span-2 bg-primary rounded-lg p-6 text-white shadow-lg shadow-blue-900/10">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-bold opacity-80 uppercase tracking-wider font-label">GAINS AUJOURD'HUI</span>
                <span class="material-symbols-outlined bg-white/10 p-2 rounded-lg">payments</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-extrabold">{{ number_format($gains_jour, 0, ',', ' ') }} XOF</span>
                <span class="text-emerald-300 text-sm font-bold flex items-center">
                    <span class="material-symbols-outlined text-xs">arrow_upward</span> +12%
                </span>
            </div>
            <div class="mt-6 flex gap-4 text-xs font-semibold opacity-90">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">local_shipping</span> {{ $livraisons_count }} Livraisons
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">schedule</span> {{ $temps_travail }}
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg p-5 border border-slate-100 shadow-sm">
            <div class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1 font-label">Bonus total</div>
            <div class="text-2xl font-extrabold text-primary">+{{ number_format($bonus_total, 0, ',', ' ') }} XOF</div>
        </div>
        <div class="bg-white rounded-lg p-5 border border-slate-100 shadow-sm">
            <div class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1 font-label">Distance</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $distance_totale }} km</div>
        </div>
    </div>

    <!-- Missions List -->
    <section class="space-y-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">Prochaines étapes</h3>
            <button class="text-sm font-bold text-primary">Tout voir</button>
        </div>

        @forelse($missions as $mission)
            <div class="bg-white rounded-lg p-5 border border-slate-100 shadow-sm">
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center gap-2 {{ $mission->statut === 'en_route' ? 'bg-emerald-50' : 'bg-blue-50' }} px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full {{ $mission->statut === 'en_route' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                        <span class="text-[10px] font-bold {{ $mission->statut === 'en_route' ? 'text-emerald-700' : 'text-blue-700' }} uppercase tracking-widest font-label">
                            {{ ucfirst(str_replace('_', ' ', $mission->statut)) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Bonus</span>
                        <span class="text-emerald-600 font-extrabold">+{{ number_format($mission->bonus ?? 500, 0, ',', ' ') }} XOF</span>
                    </div>
                </div>
                <div class="flex gap-4 mb-6">
                    <div class="flex flex-col items-center gap-1 py-1">
                        <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1">radio_button_checked</span>
                        <div class="w-0.5 h-8 bg-slate-100"></div>
                        <span class="material-symbols-outlined text-slate-300 text-xl">location_on</span>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Point de collecte</p>
                            <p class="text-sm font-bold text-slate-900">{{ $mission->adresse_enlevement }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Destination</p>
                            <p class="text-sm font-bold text-slate-900">{{ $mission->adresse_livraison }}</p>
                        </div>
                    </div>
                </div>
                @if($mission->statut === 'en_route')
                    <button class="w-full bg-primary text-white font-bold py-3.5 rounded-lg text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 active:scale-[0.98] transition-transform">
                        Continuer la livraison
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                @elseif($mission->statut === 'assignee')
                    <button class="w-full bg-primary text-white font-bold py-3.5 rounded-lg text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20 active:scale-[0.98] transition-transform">
                        Démarrer la mission
                        <span class="material-symbols-outlined text-lg">play_arrow</span>
                    </button>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg p-12 border border-dashed border-slate-200 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-2">task_alt</span>
                <p>Aucune mission assignée pour le moment.</p>
            </div>
        @endforelse
    </section>

    <!-- Route Optimization Card -->
    <section class="mt-8 mb-4">
        <div class="relative h-44 w-full rounded-lg overflow-hidden group">
            <img class="w-full h-full object-cover brightness-[0.4] group-hover:brightness-50 transition-all duration-500" src="https://images.unsplash.com/photo-1586717791821-3f44a563dc4c?auto=format&fit=crop&q=80&w=1000" />
            <div class="absolute inset-0 p-6 flex flex-col justify-end">
                <h4 class="text-white font-bold text-lg">Optimisation de l'itinéraire</h4>
                <p class="text-white/70 text-xs mt-1">Gagnez 15 min en évitant le centre-ville</p>
            </div>
            <div class="absolute top-4 right-4">
                <div class="bg-white/10 backdrop-blur-md p-2 rounded-lg text-white border border-white/20">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">explore</span>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- BottomNavBar (Livreur) --}}
<nav class="fixed bottom-0 w-full z-50 bg-white/90 backdrop-blur-lg rounded-t-xl border-t border-slate-100 flex justify-around items-center h-20 pb-safe px-4 shadow-[0_-4px_20px_rgba(0,0,0,0.03)]">
    <button class="flex flex-col items-center justify-center text-primary scale-105 transition-transform">
        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">assignment</span>
        <span class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Missions</span>
    </button>
    <button class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-2xl">payments</span>
        <span class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Gains</span>
    </button>
    <button class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-2xl">explore</span>
        <span class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Itinéraires</span>
    </button>
    <button class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-2xl">person</span>
        <span class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Profil</span>
    </button>
</nav>

<!-- Contextual FAB -->
<button class="fixed bottom-24 right-6 w-14 h-14 bg-emerald-500 text-white rounded-full shadow-xl shadow-emerald-500/20 flex items-center justify-center active:scale-90 transition-transform">
    <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">add_task</span>
</button>
@endsection
