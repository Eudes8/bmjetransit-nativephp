@extends('layouts.app')

@section('title', 'Mes produits')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes produits</h1>
        <a href="{{ route('espace.produits.create') }}" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">
            <i class="fa-solid fa-plus mr-1"></i> Ajouter
        </a>
    </div>

    {{-- Nav espace --}}
    <nav class="flex gap-1 mb-6 bg-white rounded-lg border p-1">
        <a href="{{ route('espace.dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Tableau de bord</a>
        <a href="{{ route('espace.produits.index') }}" class="px-4 py-2 rounded-md text-sm font-medium bg-bmje-600 text-white">Produits</a>
        <a href="{{ route('espace.commandes') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Commandes</a>
        <a href="{{ route('espace.finances') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">Finances</a>
    </nav>

    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Produit</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Categorie</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Prix</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Stock</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ventes</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($produits as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $p->nom }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $p->categorie->nom ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <span class="font-medium">{{ number_format($p->prix_actuel, 0, ',', ' ') }}</span>
                            @if($p->en_promo) <span class="text-xs text-red-500 ml-1">PROMO</span> @endif
                        </td>
                        <td class="px-6 py-3">{{ $p->stock ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $p->nombre_ventes }}</td>
                        <td class="px-6 py-3">
                            @php $c = ['actif'=>'bg-green-100 text-green-700','inactif'=>'bg-gray-100 text-gray-500','en_rupture'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$p->statut] ?? '' }}">{{ ucfirst(str_replace('_',' ',$p->statut)) }}</span>
                        </td>
                        <td class="px-6 py-3 flex gap-2">
                            <a href="{{ route('espace.produits.edit', $p) }}" class="text-bmje-600 hover:underline text-xs">Modifier</a>
                            <form method="POST" action="{{ route('espace.produits.destroy', $p) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ce produit ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline text-xs">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Aucun produit. <a href="{{ route('espace.produits.create') }}" class="text-bmje-600 hover:underline">Ajouter un produit</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $produits->links() }}</div>
</div>
@endsection
