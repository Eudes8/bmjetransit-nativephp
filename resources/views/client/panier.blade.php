@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mon panier <span class="text-gray-400 font-normal">({{ $nombre }} article{{ $nombre > 1 ? 's' : '' }})</span></h1>

    @if(count($panier) > 0)
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                @foreach($parEntreprise as $eid => $groupe)
                    <div class="bg-white rounded-xl border">
                        <div class="px-6 py-3 border-b bg-gray-50 flex items-center gap-2">
                            <i class="fa-solid fa-store text-bmje-600 text-sm"></i>
                            <span class="font-medium text-sm text-gray-700">{{ $groupe['entreprise_nom'] }}</span>
                        </div>
                        @foreach($groupe['items'] as $item)
                            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50 last:border-0">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-box text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $item['nom'] }}</p>
                                        <p class="text-xs text-gray-400">{{ number_format($item['prix'], 0, ',', ' ') }} XOF</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <form method="POST" action="{{ route('panier.modifier', $item['produit_id']) }}" class="flex items-center gap-1">
                                        @csrf @method('PATCH')
                                        <button type="submit" name="quantite" value="{{ max(0, $item['quantite'] - 1) }}"
                                                class="w-7 h-7 rounded border text-sm flex items-center justify-center hover:bg-gray-100">-</button>
                                        <span class="w-8 text-center text-sm font-medium">{{ $item['quantite'] }}</span>
                                        <button type="submit" name="quantite" value="{{ $item['quantite'] + 1 }}"
                                                class="w-7 h-7 rounded border text-sm flex items-center justify-center hover:bg-gray-100">+</button>
                                    </form>
                                    <p class="font-bold text-gray-800 w-24 text-right">{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }}</p>
                                    <form method="POST" action="{{ route('panier.supprimer', $item['produit_id']) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <form method="POST" action="{{ route('panier.vider') }}" class="text-right">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-sm hover:underline">Vider le panier</button>
                </form>
            </div>

            {{-- Resume --}}
            <div>
                <div class="bg-white rounded-xl border p-6 sticky top-4">
                    <h3 class="font-semibold text-gray-800 mb-4">Resume</h3>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between"><span class="text-gray-500">Sous-total</span><span>{{ number_format($total, 0, ',', ' ') }} XOF</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Livraison</span><span class="text-gray-400 text-xs">Calcule au checkout</span></div>
                    </div>
                    <div class="border-t pt-3 mb-4">
                        <div class="flex justify-between font-bold"><span>Total</span><span class="text-bmje-700">{{ number_format($total, 0, ',', ' ') }} XOF</span></div>
                    </div>
                    <a href="{{ route('checkout') }}" class="block w-full bg-bmje-600 text-white text-center py-3 rounded-lg font-medium hover:bg-bmje-700 transition">
                        Commander
                    </a>
                    <a href="{{ route('catalogue') }}" class="block text-center text-bmje-600 text-sm hover:underline mt-3">Continuer les achats</a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl border p-12 text-center">
            <i class="fa-solid fa-cart-shopping text-gray-300 text-4xl mb-4 block"></i>
            <p class="text-gray-500 mb-4">Votre panier est vide.</p>
            <a href="{{ route('catalogue') }}" class="text-bmje-600 hover:underline">Decouvrir le catalogue</a>
        </div>
    @endif
</div>
@endsection
