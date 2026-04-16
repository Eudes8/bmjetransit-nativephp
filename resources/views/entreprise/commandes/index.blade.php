@extends('layouts.app')

@section('title', 'Commandes entreprise')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Commandes</h1>

    <nav class="flex gap-1 mb-6 bg-white rounded-lg border p-1">
        <a href="{{ route('espace.dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Tableau de bord</a>
        <a href="{{ route('espace.produits.index') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Produits</a>
        <a href="{{ route('espace.commandes') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-bmje-600 text-white">Commandes</a>
        <a href="{{ route('espace.finances') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Finances</a>
    </nav>

    {{-- Filtres --}}
    <form method="GET" class="bg-white rounded-xl border p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Statut</label>
            <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Tous</option>
                @foreach(['en_attente', 'confirmee', 'en_preparation', 'prete', 'en_livraison', 'livree', 'annulee'] as $s)
                    <option value="{{ $s }}" {{ request('statut') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">Filtrer</button>
    </form>

    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">N.</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Client</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Votre part</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($commandes as $cmd)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="{{ route('espace.commandes.show', $cmd) }}" class="text-bmje-600 font-medium hover:underline">{{ $cmd->numero }}</a>
                        </td>
                        <td class="px-6 py-3">{{ $cmd->client->nom_complet }}</td>
                        <td class="px-6 py-3">{{ number_format($cmd->montant_total, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3 font-medium text-green-600">{{ number_format($cmd->montant_entreprise, 0, ',', ' ') }}</td>
                        <td class="px-6 py-3">
                            @php $c = ['en_attente'=>'bg-yellow-100 text-yellow-700','confirmee'=>'bg-blue-100 text-blue-700','livree'=>'bg-green-100 text-green-700','annulee'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$cmd->statut] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst(str_replace('_',' ',$cmd->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $cmd->created_at->format('d/m H:i') }}</td>
                        <td class="px-6 py-3 flex gap-2">
                            @if($cmd->statut === 'en_attente')
                                <form method="POST" action="{{ route('espace.commandes.confirmer', $cmd) }}">@csrf <button class="text-green-600 text-xs hover:underline">Confirmer</button></form>
                            @endif
                            @if(in_array($cmd->statut, ['confirmee', 'en_preparation']))
                                <form method="POST" action="{{ route('espace.commandes.prete', $cmd) }}">@csrf <button class="text-bmje-600 text-xs hover:underline">Prete</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $commandes->withQueryString()->links() }}</div>
</div>
@endsection
