@extends('layouts.app')

@section('title', 'Finances')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Finances</h1>

    <nav class="flex gap-1 mb-6 bg-white rounded-lg border p-1">
        <a href="{{ route('espace.dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Tableau de bord</a>
        <a href="{{ route('espace.produits.index') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Produits</a>
        <a href="{{ route('espace.commandes') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Commandes</a>
        <a href="{{ route('espace.finances') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-bmje-600 text-white">Finances</a>
    </nav>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border p-5 text-center">
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['solde_disponible'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">Solde disponible (XOF)</p>
        </div>
        <div class="bg-white rounded-xl border p-5 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['solde_en_attente'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">En attente de versement</p>
        </div>
        <div class="bg-white rounded-xl border p-5 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_gagne'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total gagne</p>
        </div>
        <div class="bg-white rounded-xl border p-5 text-center">
            <p class="text-2xl font-bold text-bmje-700">{{ number_format($stats['total_retire'], 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total retire</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Demander versement --}}
        <div>
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Demander un versement</h3>
                <form method="POST" action="{{ route('espace.versement') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Montant (XOF)</label>
                        <input type="number" name="montant" required min="5000" max="{{ $stats['solde_disponible'] }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Minimum : 5 000 XOF</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Mode de paiement</label>
                        <select name="mode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="orange_money">Orange Money</option>
                            <option value="mtn_momo">MTN MoMo</option>
                            <option value="wave">Wave</option>
                            <option value="virement">Virement bancaire</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Numero de compte / telephone</label>
                        <input type="text" name="numero_compte" required placeholder="+225..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <button type="submit" class="w-full bg-bmje-600 text-white py-2 rounded-lg text-sm hover:bg-bmje-700"
                            {{ $stats['solde_disponible'] < 5000 ? 'disabled' : '' }}>
                        Demander le versement
                    </button>
                </form>
            </div>
        </div>

        {{-- Historique versements --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border mb-6">
                <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Historique des versements</h3></div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Mode</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Compte</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Demande</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($versements as $v)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-bold">{{ number_format($v->montant, 0, ',', ' ') }} XOF</td>
                                <td class="px-6 py-3 capitalize">{{ str_replace('_',' ',$v->mode) }}</td>
                                <td class="px-6 py-3">{{ $v->numero_compte }}</td>
                                <td class="px-6 py-3">
                                    @php $c = ['en_attente'=>'bg-yellow-100 text-yellow-700','effectue'=>'bg-green-100 text-green-700','rejete'=>'bg-red-100 text-red-700']; @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$v->statut] ?? '' }}">{{ ucfirst(str_replace('_',' ',$v->statut)) }}</span>
                                </td>
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $v->date_demande->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucun versement.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $versements->links() }}</div>

            {{-- Commandes payees --}}
            <div class="bg-white rounded-xl border mt-6">
                <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Dernieres ventes</h3></div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Commande</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Votre part</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commandes_payees as $cmd)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-bmje-600">{{ $cmd->numero }}</td>
                                <td class="px-6 py-3">{{ number_format($cmd->montant_total, 0, ',', ' ') }}</td>
                                <td class="px-6 py-3 font-medium text-green-600">{{ number_format($cmd->montant_entreprise, 0, ',', ' ') }}</td>
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $cmd->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
