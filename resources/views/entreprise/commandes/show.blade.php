@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('espace.commandes') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Retour</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Statut + actions --}}
            <div class="bg-white rounded-xl border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $commande->numero }}</h1>
                        <p class="text-sm text-gray-400">{{ $commande->created_at->format('d/m/Y a H:i') }}</p>
                    </div>
                    @php
                        $couleurs = ['en_attente'=>'bg-yellow-100 text-yellow-700','confirmee'=>'bg-blue-100 text-blue-700','prete'=>'bg-cyan-100 text-cyan-700','en_livraison'=>'bg-orange-100 text-orange-700','livree'=>'bg-green-100 text-green-700','annulee'=>'bg-red-100 text-red-700'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $couleurs[$commande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                </div>
                <div class="flex gap-2">
                    @if($commande->statut === 'en_attente')
                        <form method="POST" action="{{ route('espace.commandes.confirmer', $commande) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Confirmer</button>
                        </form>
                    @endif
                    @if(in_array($commande->statut, ['confirmee', 'en_preparation']))
                        <form method="POST" action="{{ route('espace.commandes.prete', $commande) }}">
                            @csrf
                            <button class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">Marquer prete</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Produits --}}
            <div class="bg-white rounded-xl border">
                <div class="px-6 py-4 border-b"><h3 class="font-semibold text-gray-800">Articles</h3></div>
                <div class="divide-y divide-gray-100">
                    @foreach($commande->commandeProduits as $ligne)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-800">{{ $ligne->produit->nom }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} XOF x {{ $ligne->quantite }}</p>
                            </div>
                            <p class="font-bold">{{ number_format($ligne->montant, 0, ',', ' ') }} XOF</p>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 text-sm space-y-1">
                    <div class="flex justify-between"><span class="text-gray-500">Sous-total</span><span>{{ number_format($commande->montant_produits, 0, ',', ' ') }} XOF</span></div>
                    <div class="flex justify-between text-green-600 font-medium"><span>Votre part</span><span>{{ number_format($commande->montant_entreprise, 0, ',', ' ') }} XOF</span></div>
                    <div class="flex justify-between text-xs text-gray-400"><span>Commission BMJE</span><span>{{ number_format($commande->commission_bmje, 0, ',', ' ') }} XOF</span></div>
                </div>
            </div>

            {{-- Livraison --}}
            @if($commande->livraison)
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Livraison</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><p class="text-gray-500">Tracking</p><p class="font-medium">{{ $commande->livraison->numero_tracking }}</p></div>
                        <div><p class="text-gray-500">Statut</p><p class="font-medium capitalize">{{ str_replace('_',' ',$commande->livraison->statut) }}</p></div>
                        @if($commande->livraison->livreur)
                            <div><p class="text-gray-500">Livreur</p><p class="font-medium">{{ $commande->livraison->livreur->user->nom_complet }}</p></div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Client</h3>
                <p class="text-sm font-medium">{{ $commande->client->nom_complet }}</p>
                <p class="text-sm text-gray-500">{{ $commande->client->telephone }}</p>
            </div>
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Livraison</h3>
                <p class="text-sm">{{ $commande->adresse_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->ville_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->telephone_livraison }}</p>
            </div>
            @if($commande->notes_client)
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Notes client</h3>
                    <p class="text-sm text-gray-600">{{ $commande->notes_client }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
