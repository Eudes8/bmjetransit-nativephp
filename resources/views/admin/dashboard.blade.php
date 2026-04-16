@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">CA du mois</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} <span class="text-sm font-normal">XOF</span></p>
                </div>
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Aujourd'hui : {{ number_format($stats['ca_jour'], 0, ',', ' ') }} XOF</p>
        </div>

        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Commandes en cours</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['commandes_en_cours'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-shopping-cart text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Aujourd'hui : {{ $stats['commandes_jour'] }} nouvelles</p>
        </div>

        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Entreprises</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_entreprises'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
            </div>
            @if($stats['entreprises_en_attente'] > 0)
                <p class="text-xs text-orange-500 mt-3">{{ $stats['entreprises_en_attente'] }} en attente d'approbation</p>
            @else
                <p class="text-xs text-gray-400 mt-3">Toutes approuvees</p>
            @endif
        </div>

        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Livreurs</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_livreurs'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-motorcycle text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-green-500 mt-3">{{ $stats['livreurs_disponibles'] }} disponibles</p>
        </div>
    </div>

    {{-- Dernieres commandes --}}
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Dernieres commandes</h3>
            <a href="{{ route('admin.commandes') }}" class="text-bmje-600 text-sm hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">N.</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Client</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Entreprise</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dernieres_commandes as $cmd)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.commandes.show', $cmd) }}" class="text-bmje-600 font-medium hover:underline">{{ $cmd->numero }}</a>
                            </td>
                            <td class="px-6 py-3 text-gray-700">{{ $cmd->client->nom_complet }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $cmd->entreprise->raison_sociale }}</td>
                            <td class="px-6 py-3 font-medium">{{ number_format($cmd->montant_total, 0, ',', ' ') }} XOF</td>
                            <td class="px-6 py-3">
                                @php
                                    $couleurs = [
                                        'en_attente' => 'bg-yellow-100 text-yellow-700',
                                        'confirmee' => 'bg-blue-100 text-blue-700',
                                        'en_preparation' => 'bg-indigo-100 text-indigo-700',
                                        'prete' => 'bg-cyan-100 text-cyan-700',
                                        'enlevee' => 'bg-teal-100 text-teal-700',
                                        'en_livraison' => 'bg-orange-100 text-orange-700',
                                        'livree' => 'bg-green-100 text-green-700',
                                        'annulee' => 'bg-red-100 text-red-700',
                                        'litige' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $couleurs[$cmd->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ str_replace('_', ' ', ucfirst($cmd->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-400 text-xs">{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Aucune commande pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
