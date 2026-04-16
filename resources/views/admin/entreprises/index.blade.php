@extends('layouts.admin')

@section('title', 'Entreprises')

@section('content')
    <form method="GET" class="bg-white rounded-xl border p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Recherche</label>
            <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Raison sociale..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Statut</label>
            <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Tous</option>
                @foreach(['en_attente', 'approuvee', 'suspendue', 'rejetee'] as $s)
                    <option value="{{ $s }}" {{ request('statut') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">Filtrer</button>
    </form>

    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Entreprise</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Proprietaire</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ville</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Forfait</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Commission</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ventes</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entreprises as $ent)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.entreprises.show', $ent) }}" class="text-bmje-600 font-medium hover:underline">
                                {{ $ent->raison_sociale }}
                            </a>
                            @if($ent->sigle) <span class="text-gray-400 text-xs">({{ $ent->sigle }})</span> @endif
                        </td>
                        <td class="px-6 py-3">{{ $ent->proprietaire->nom_complet }}</td>
                        <td class="px-6 py-3">{{ $ent->ville }}</td>
                        <td class="px-6 py-3">{{ $ent->abonnementActif?->forfait?->nom ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $ent->commission_taux }}%</td>
                        <td class="px-6 py-3">{{ $ent->nombre_ventes }}</td>
                        <td class="px-6 py-3">
                            @php
                                $c = ['en_attente'=>'bg-yellow-100 text-yellow-700','approuvee'=>'bg-green-100 text-green-700','suspendue'=>'bg-red-100 text-red-700','rejetee'=>'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$ent->statut] ?? '' }}">{{ ucfirst(str_replace('_',' ',$ent->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3">
                            @if($ent->statut === 'en_attente')
                                <form method="POST" action="{{ route('admin.entreprises.approuver', $ent) }}" class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:underline text-xs">Approuver</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Aucune entreprise.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $entreprises->withQueryString()->links() }}</div>
@endsection
