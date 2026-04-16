@extends('layouts.app')

@section('title', 'Espace Entreprise')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $entreprise->raison_sociale }}</h1>
            <p class="text-gray-500 text-sm mt-1">Tableau de bord entreprise</p>
        </div>
        <a href="{{ route('espace.produits.create') }}" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">
            <i class="fa-solid fa-plus mr-1"></i> Ajouter un produit
        </a>
    </div>

    {{-- Navigation espace --}}
    <nav class="flex gap-1 mb-8 bg-white rounded-lg border p-1">
        <a href="{{ route('espace.dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-bmje-600 text-white">Tableau de bord</a>
        <a href="{{ route('espace.produits.index') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Produits</a>
        <a href="{{ route('espace.commandes') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Commandes</a>
        <a href="{{ route('espace.finances') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Finances</a>
    </nav>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $stats['produits_actifs'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Produits actifs</p>
        </div>
        <div class="bg-white rounded-xl border p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $stats['commandes_en_cours'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Commandes en cours</p>
        </div>
        <div class="bg-white rounded-xl border p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $stats['commandes_mois'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Commandes ce mois</p>
        </div>
        <div class="bg-white rounded-xl border p-4 text-center col-span-2 md:col-span-1">
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">CA du mois (XOF)</p>
        </div>
        <div class="bg-white rounded-xl border p-4 text-center col-span-2 md:col-span-2 lg:col-span-2">
            <p class="text-2xl font-bold text-bmje-700">{{ number_format($stats['solde_disponible'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">Solde disponible (XOF)</p>
        </div>
    </div>

    {{-- Dernieres commandes --}}
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Dernieres commandes</h3>
            <a href="{{ route('espace.commandes') }}" class="text-bmje-600 text-sm hover:underline">Voir tout</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">N.</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Client</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dernieres_commandes as $cmd)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3"><a href="{{ route('espace.commandes.show', $cmd) }}" class="text-bmje-600 hover:underline">{{ $cmd->numero }}</a></td>
                        <td class="px-6 py-3">{{ $cmd->client->nom_complet }}</td>
                        <td class="px-6 py-3 font-medium">{{ number_format($cmd->montant_entreprise, 0, ',', ' ') }} XOF</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_',' ',$cmd->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
