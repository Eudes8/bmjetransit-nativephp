@extends('layouts.app')

@section('title', 'Suivi livraison')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    {{-- Barre de recherche --}}
    <form method="POST" action="{{ route('tracking.rechercher') }}" class="bg-white rounded-xl border p-6 mb-8">
        @csrf
        <label class="block text-sm font-medium text-gray-700 mb-2">Numero de tracking</label>
        <div class="flex gap-3">
            <input type="text" name="numero" value="{{ $livraison->numero_tracking ?? '' }}" required placeholder="TRK-XXXXXXXX"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
            <button type="submit" class="bg-bmje-600 text-white px-6 py-2.5 rounded-lg text-sm hover:bg-bmje-700">Rechercher</button>
        </div>
    </form>

    @if(isset($livraison))
        {{-- Statut actuel --}}
        <div class="bg-white rounded-xl border p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $livraison->numero_tracking }}</h2>
                    <p class="text-sm text-gray-500">Commande {{ $livraison->commande->numero }}</p>
                </div>
                @php
                    $c = ['assignee'=>'bg-blue-100 text-blue-700','enlevee'=>'bg-indigo-100 text-indigo-700','en_route'=>'bg-orange-100 text-orange-700','livree'=>'bg-green-100 text-green-700','echec'=>'bg-red-100 text-red-700','retour'=>'bg-gray-100 text-gray-700'];
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $c[$livraison->statut] ?? '' }}">
                    {{ ucfirst(str_replace('_', ' ', $livraison->statut)) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">De</p>
                    <p class="text-gray-800">{{ $livraison->adresse_enlevement }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Vers</p>
                    <p class="text-gray-800">{{ $livraison->adresse_livraison }}</p>
                </div>
                @if($livraison->livreur)
                    <div>
                        <p class="text-gray-500">Livreur</p>
                        <p class="text-gray-800">{{ $livraison->livreur->user->nom_complet }}</p>
                    </div>
                @endif
                @if($livraison->date_livraison_estimee)
                    <div>
                        <p class="text-gray-500">Livraison estimee</p>
                        <p class="text-gray-800">{{ $livraison->date_livraison_estimee->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Historique --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Historique</h3>
            <div class="space-y-4">
                @forelse($livraison->suivis as $suivi)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-bmje-600 rounded-full"></div>
                            @if(!$loop->last)
                                <div class="w-0.5 h-full bg-gray-200 mt-1"></div>
                            @endif
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $suivi->statut)) }}</p>
                            @if($suivi->description)
                                <p class="text-xs text-gray-500">{{ $suivi->description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $suivi->horodatage->format('d/m/Y a H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Aucun evenement pour le moment.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection
