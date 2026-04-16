@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('client.commandes') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Mes commandes</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $commande->numero }}</h1>
                        <p class="text-sm text-gray-400">{{ $commande->created_at->format('d/m/Y a H:i') }}</p>
                    </div>
                    @php
                        $couleurs = [
                            'en_attente' => 'bg-yellow-100 text-yellow-700',
                            'confirmee' => 'bg-blue-100 text-blue-700',
                            'en_livraison' => 'bg-orange-100 text-orange-700',
                            'livree' => 'bg-green-100 text-green-700',
                            'annulee' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $couleurs[$commande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                </div>

                @if($commande->peutEtreAnnulee())
                    <form method="POST" action="{{ route('client.commandes.annuler', $commande) }}" class="inline"
                          onsubmit="return confirm('Etes-vous sur de vouloir annuler cette commande ?')">
                        @csrf
                        <button class="text-red-500 text-sm hover:underline">Annuler la commande</button>
                    </form>
                @endif
            </div>

            {{-- Produits --}}
            <div class="bg-white rounded-xl border">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Articles commandes</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($commande->commandeProduits as $ligne)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-box text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $ligne->produit->nom }}</p>
                                    <p class="text-xs text-gray-400">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} XOF x {{ $ligne->quantite }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-gray-800">{{ number_format($ligne->montant, 0, ',', ' ') }} XOF</p>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sous-total</span>
                        <span>{{ number_format($commande->montant_produits, 0, ',', ' ') }} XOF</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Frais de livraison</span>
                        <span>{{ number_format($commande->frais_livraison, 0, ',', ' ') }} XOF</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Total</span>
                        <span class="text-bmje-700">{{ number_format($commande->montant_total, 0, ',', ' ') }} XOF</span>
                    </div>
                </div>
            </div>

            {{-- Suivi livraison --}}
            @if($commande->livraison)
                <div class="bg-white rounded-xl border p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Suivi de livraison</h3>
                        <a href="{{ route('tracking', $commande->livraison->numero_tracking) }}" class="text-bmje-600 text-sm hover:underline">
                            {{ $commande->livraison->numero_tracking }}
                        </a>
                    </div>
                    @if($commande->livraison->livreur)
                        <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-bmje-100 text-bmje-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-motorcycle"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm">{{ $commande->livraison->livreur->user->nom_complet }}</p>
                                <p class="text-xs text-gray-400">Votre livreur</p>
                            </div>
                        </div>
                    @endif
                    <div class="space-y-3">
                        @foreach($commande->livraison->suivis as $suivi)
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-2.5 h-2.5 bg-bmje-600 rounded-full"></div>
                                    @if(!$loop->last) <div class="w-0.5 h-full bg-gray-200 mt-1"></div> @endif
                                </div>
                                <div class="pb-3">
                                    <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $suivi->statut)) }}</p>
                                    @if($suivi->description) <p class="text-xs text-gray-500">{{ $suivi->description }}</p> @endif
                                    <p class="text-xs text-gray-400">{{ $suivi->horodatage->format('d/m/Y a H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Entreprise</h3>
                <p class="font-medium text-sm">{{ $commande->entreprise->raison_sociale }}</p>
                <p class="text-sm text-gray-500">{{ $commande->entreprise->ville }}</p>
            </div>

            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Adresse de livraison</h3>
                <p class="text-sm">{{ $commande->adresse_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->ville_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->telephone_livraison }}</p>
            </div>

            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Paiement</h3>
                <p class="text-sm"><i class="fa-solid fa-credit-card mr-2 text-gray-400"></i> {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}</p>
                <p class="text-sm mt-1">
                    <span class="px-2 py-0.5 rounded text-xs {{ $commande->paiement_statut === 'paye' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->paiement_statut)) }}
                    </span>
                </p>
            </div>

            @if($commande->notes_client)
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Notes</h3>
                    <p class="text-sm text-gray-600">{{ $commande->notes_client }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
