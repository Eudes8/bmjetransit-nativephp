@extends('layouts.admin')

@section('title', 'Finances')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">CA total</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['ca_total'], 0, ',', ' ') }} <span class="text-sm font-normal">XOF</span></p>
                </div>
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Commissions gagnees</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} <span class="text-sm font-normal">XOF</span></p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Frais livraison collectes</p>
                    <p class="text-2xl font-bold text-bmje-700 mt-1">{{ number_format($stats['total_livraison'], 0, ',', ' ') }} <span class="text-sm font-normal">XOF</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-truck text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Versements en attente</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($stats['versements_en_attente'], 0, ',', ' ') }} <span class="text-sm font-normal">XOF</span></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Versements en attente --}}
    <div class="bg-white rounded-xl border mb-6">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Demandes de versement</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Entreprise</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Mode</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Compte</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Demande le</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($versements as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium">{{ $v->entreprise->raison_sociale }}</td>
                        <td class="px-6 py-3 font-bold">{{ number_format($v->montant, 0, ',', ' ') }} XOF</td>
                        <td class="px-6 py-3 capitalize">{{ str_replace('_', ' ', $v->mode) }}</td>
                        <td class="px-6 py-3">{{ $v->numero_compte }}</td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $v->date_demande->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3">
                            @php $c = ['en_attente'=>'bg-yellow-100 text-yellow-700','effectue'=>'bg-green-100 text-green-700','rejete'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$v->statut] ?? '' }}">{{ ucfirst(str_replace('_',' ',$v->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3">
                            @if($v->statut === 'en_attente')
                                <form method="POST" action="{{ route('admin.versements.effectuer', $v) }}">
                                    @csrf
                                    <button class="text-green-600 hover:underline text-xs font-medium">Marquer effectue</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Aucune demande de versement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $versements->withQueryString()->links() }}</div>

    {{-- Transactions recentes --}}
    <div class="bg-white rounded-xl border mt-6">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Transactions recentes</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Type</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Montant</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Mode</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 capitalize">{{ str_replace('_', ' ', $t->type) }}</td>
                        <td class="px-6 py-3 font-medium">{{ number_format($t->montant, 0, ',', ' ') }} XOF</td>
                        <td class="px-6 py-3 capitalize">{{ str_replace('_', ' ', $t->mode) }}</td>
                        <td class="px-6 py-3">
                            @php $c = ['en_attente'=>'bg-yellow-100 text-yellow-700','complete'=>'bg-green-100 text-green-700','echoue'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$t->statut] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst(str_replace('_',' ',$t->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $t->date_transaction->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune transaction.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
