@extends('layouts.admin')

@section('title', 'Commande ' . $commande->numero)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.commandes') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Retour aux commandes</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Infos principales --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Statut + actions --}}
            <div class="bg-white rounded-xl border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">{{ $commande->numero }}</h2>
                        <p class="text-sm text-gray-400">Passee le {{ $commande->created_at->format('d/m/Y a H:i') }}</p>
                    </div>
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
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $couleurs[$commande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                </div>

                {{-- Changer statut --}}
                <div class="flex flex-wrap gap-2">
                    @foreach(['confirmee', 'en_preparation', 'prete', 'enlevee', 'en_livraison', 'livree'] as $s)
                        @if($commande->statut !== $s)
                            <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" class="inline">
                                @csrf
                                <input type="hidden" name="statut" value="{{ $s }}">
                                <button class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs hover:bg-gray-50">
                                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Produits --}}
            <div class="bg-white rounded-xl border">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Produits commandes</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Produit</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Qte</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Prix unit.</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commande->commandeProduits as $ligne)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $ligne->produit->nom }}</td>
                                <td class="px-6 py-3">{{ $ligne->quantite }}</td>
                                <td class="px-6 py-3">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} XOF</td>
                                <td class="px-6 py-3 font-medium">{{ number_format($ligne->montant, 0, ',', ' ') }} XOF</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-medium">Sous-total</td>
                            <td class="px-6 py-3 font-medium">{{ number_format($commande->montant_produits, 0, ',', ' ') }} XOF</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm text-gray-500">Frais de livraison</td>
                            <td class="px-6 py-3">{{ number_format($commande->frais_livraison, 0, ',', ' ') }} XOF</td>
                        </tr>
                        <tr class="font-bold">
                            <td colspan="3" class="px-6 py-3 text-right">Total</td>
                            <td class="px-6 py-3 text-bmje-700">{{ number_format($commande->montant_total, 0, ',', ' ') }} XOF</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Assigner livreur --}}
            @if(!$commande->livraison && in_array($commande->statut, ['prete', 'confirmee', 'en_preparation']))
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Assigner un livreur</h3>
                    <form method="POST" action="{{ route('admin.commandes.assigner', $commande) }}" class="flex gap-4">
                        @csrf
                        <select name="livreur_id" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                            <option value="">Choisir un livreur</option>
                            @foreach($livreurs_disponibles as $liv)
                                <option value="{{ $liv->id }}">{{ $liv->user->nom_complet }} - {{ $liv->type_vehicule }} ({{ $liv->zone_activite }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-bmje-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-bmje-700">Assigner</button>
                    </form>
                </div>
            @endif

            {{-- Livraison --}}
            @if($commande->livraison)
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Livraison</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <p class="text-gray-500">Tracking</p>
                            <p class="font-medium">{{ $commande->livraison->numero_tracking }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Livreur</p>
                            <p class="font-medium">{{ $commande->livraison->livreur->user->nom_complet }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Statut</p>
                            <p class="font-medium capitalize">{{ str_replace('_', ' ', $commande->livraison->statut) }}</p>
                        </div>
                    </div>
                    @if($commande->livraison->suivis->count())
                        <div class="border-t pt-4 space-y-3">
                            @foreach($commande->livraison->suivis as $suivi)
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 bg-bmje-600 rounded-full mt-1.5"></div>
                                    <div>
                                        <p class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $suivi->statut)) }}</p>
                                        <p class="text-xs text-gray-400">{{ $suivi->horodatage->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Client --}}
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Client</h3>
                <p class="text-sm font-medium">{{ $commande->client->nom_complet }}</p>
                <p class="text-sm text-gray-500">{{ $commande->client->email }}</p>
                <p class="text-sm text-gray-500">{{ $commande->client->telephone }}</p>
            </div>

            {{-- Entreprise --}}
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Entreprise</h3>
                <p class="text-sm font-medium">{{ $commande->entreprise->raison_sociale }}</p>
                <p class="text-sm text-gray-500">{{ $commande->entreprise->ville }}</p>
            </div>

            {{-- Livraison --}}
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Adresse de livraison</h3>
                <p class="text-sm">{{ $commande->adresse_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->ville_livraison }}</p>
                <p class="text-sm text-gray-500">{{ $commande->telephone_livraison }}</p>
            </div>

            {{-- Finances --}}
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Repartition</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Montant produits</span>
                        <span>{{ number_format($commande->montant_produits, 0, ',', ' ') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Frais livraison</span>
                        <span>{{ number_format($commande->frais_livraison, 0, ',', ' ') }}</span>
                    </div>
                    <div class="flex justify-between text-green-600 font-medium">
                        <span>Commission BMJE</span>
                        <span>{{ number_format($commande->commission_bmje, 0, ',', ' ') }}</span>
                    </div>
                    <div class="flex justify-between font-medium border-t pt-2">
                        <span>Part entreprise</span>
                        <span>{{ number_format($commande->montant_entreprise, 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>

            {{-- Paiement --}}
            <div class="bg-white rounded-xl border p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Paiement</h3>
                <p class="text-sm"><span class="text-gray-500">Mode :</span> {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}</p>
                <p class="text-sm"><span class="text-gray-500">Statut :</span> {{ ucfirst(str_replace('_', ' ', $commande->paiement_statut)) }}</p>
            </div>
        </div>
    </div>
@endsection
