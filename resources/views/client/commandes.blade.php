@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mes commandes</h1>

    <div class="space-y-4">
        @forelse($commandes as $cmd)
            <div class="bg-white rounded-xl border p-6 hover:shadow-sm transition">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('client.commandes.show', $cmd) }}" class="text-bmje-600 font-bold hover:underline">{{ $cmd->numero }}</a>
                            @php
                                $couleurs = [
                                    'en_attente' => 'bg-yellow-100 text-yellow-700',
                                    'confirmee' => 'bg-blue-100 text-blue-700',
                                    'en_preparation' => 'bg-indigo-100 text-indigo-700',
                                    'prete' => 'bg-cyan-100 text-cyan-700',
                                    'en_livraison' => 'bg-orange-100 text-orange-700',
                                    'livree' => 'bg-green-100 text-green-700',
                                    'annulee' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $couleurs[$cmd->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $cmd->statut)) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $cmd->entreprise->raison_sociale }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $cmd->created_at->format('d/m/Y a H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-800">{{ number_format($cmd->montant_total, 0, ',', ' ') }} XOF</p>
                        <p class="text-xs text-gray-400">dont {{ number_format($cmd->frais_livraison, 0, ',', ' ') }} livraison</p>
                    </div>
                </div>

                @if($cmd->livraison)
                    <div class="mt-4 pt-4 border-t flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fa-solid fa-truck"></i>
                            <span>{{ $cmd->livraison->numero_tracking }}</span>
                            <span class="px-2 py-0.5 rounded text-xs {{ $cmd->livraison->statut === 'livree' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $cmd->livraison->statut)) }}
                            </span>
                        </div>
                        <a href="{{ route('tracking', $cmd->livraison->numero_tracking) }}" class="text-bmje-600 text-sm hover:underline">Suivre</a>
                    </div>
                @endif

                <div class="mt-4 flex gap-3">
                    <a href="{{ route('client.commandes.show', $cmd) }}" class="text-bmje-600 text-sm hover:underline">Voir details</a>
                    @if($cmd->peutEtreAnnulee())
                        <form method="POST" action="{{ route('client.commandes.annuler', $cmd) }}" class="inline"
                              onsubmit="return confirm('Annuler cette commande ?')">
                            @csrf
                            <button class="text-red-500 text-sm hover:underline">Annuler</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border p-12 text-center">
                <i class="fa-solid fa-shopping-bag text-gray-300 text-4xl mb-4 block"></i>
                <p class="text-gray-500 mb-4">Vous n'avez pas encore passe de commande.</p>
                <a href="{{ route('catalogue') }}" class="text-bmje-600 hover:underline">Decouvrir le catalogue</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $commandes->links() }}</div>
</div>
@endsection
