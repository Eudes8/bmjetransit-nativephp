@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
    {{-- Filtres --}}
    <form method="GET" class="bg-white rounded-xl border p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Recherche</label>
            <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Numero, client..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Statut</label>
            <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Tous</option>
                @foreach(['en_attente', 'confirmee', 'en_preparation', 'prete', 'enlevee', 'en_livraison', 'livree', 'annulee', 'litige'] as $s)
                    <option value="{{ $s }}" {{ request('statut') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">Filtrer</button>
        <a href="{{ route('admin.commandes') }}" class="text-gray-400 text-sm hover:text-gray-600 py-2">Reinitialiser</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">N.</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Client</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Entreprise</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Commission</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Paiement</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($commandes as $cmd)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.commandes.show', $cmd) }}" class="text-bmje-600 font-medium hover:underline">{{ $cmd->numero }}</a>
                        </td>
                        <td class="px-6 py-3">{{ $cmd->client->nom_complet }}</td>
                        <td class="px-6 py-3">{{ $cmd->entreprise->raison_sociale }}</td>
                        <td class="px-6 py-3 font-medium">{{ number_format($cmd->montant_total, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3 text-green-600">{{ number_format($cmd->commission_bmje, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded text-xs {{ $cmd->paiement_statut === 'paye' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $cmd->paiement_statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $couleurs = [
                                    'en_attente' => 'bg-yellow-100 text-yellow-700',
                                    'confirmee' => 'bg-blue-100 text-blue-700',
                                    'en_livraison' => 'bg-orange-100 text-orange-700',
                                    'livree' => 'bg-green-100 text-green-700',
                                    'annulee' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $couleurs[$cmd->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ str_replace('_', ' ', ucfirst($cmd->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $cmd->created_at->format('d/m H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $commandes->withQueryString()->links() }}</div>
@endsection
