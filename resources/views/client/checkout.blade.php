@extends('layouts.app')

@section('title', 'Passer commande')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Passer commande</h1>

    <form method="POST" action="{{ route('commandes.store') }}">
        @csrf
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Adresse livraison --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Adresse de livraison</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Adresse</label>
                            <input type="text" name="adresse_livraison" value="{{ old('adresse_livraison', auth()->user()->adresse) }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                            @error('adresse_livraison') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Ville</label>
                                <select name="ville_livraison" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone }}" {{ old('ville_livraison', auth()->user()->ville) === $zone ? 'selected' : '' }}>{{ $zone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Telephone</label>
                                <input type="text" name="telephone_livraison" value="{{ old('telephone_livraison', auth()->user()->telephone) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Paiement --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Mode de paiement</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            'orange_money' => ['Orange Money', 'fa-mobile-screen'],
                            'mtn_momo' => ['MTN MoMo', 'fa-mobile-screen'],
                            'wave' => ['Wave', 'fa-wave-square'],
                            'especes' => ['Especes', 'fa-money-bill'],
                        ] as $mode => [$label, $icon])
                            <label class="relative cursor-pointer">
                                <input type="radio" name="mode_paiement" value="{{ $mode }}" class="peer sr-only" {{ old('mode_paiement') === $mode ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-bmje-600 peer-checked:bg-bmje-50 hover:bg-gray-50">
                                    <i class="fa-solid {{ $icon }} text-xl text-gray-500 mb-2 block"></i>
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('mode_paiement') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror

                    <div class="mt-4" id="numero-paiement">
                        <label class="block text-sm text-gray-600 mb-1">Numero de paiement</label>
                        <input type="text" name="numero_paiement" value="{{ old('numero_paiement', auth()->user()->telephone) }}" placeholder="+225..."
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-xl border p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Instructions (optionnel)</h3>
                    <textarea name="notes_client" rows="2" placeholder="Instructions speciales pour la livraison..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">{{ old('notes_client') }}</textarea>
                </div>
            </div>

            {{-- Resume --}}
            <div>
                <div class="bg-white rounded-xl border p-6 sticky top-4">
                    <h3 class="font-semibold text-gray-800 mb-4">Votre commande</h3>
                    <div class="divide-y divide-gray-100 mb-4">
                        @foreach($panier as $item)
                            <div class="py-2 flex justify-between text-sm">
                                <span class="text-gray-700">{{ $item['nom'] }} <span class="text-gray-400">x{{ $item['quantite'] }}</span></span>
                                <span class="font-medium">{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t pt-3 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Sous-total</span><span>{{ number_format($total_produits, 0, ',', ' ') }} XOF</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Frais de livraison</span><span>{{ number_format($frais_livraison, 0, ',', ' ') }} XOF</span></div>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t"><span>Total</span><span class="text-bmje-700">{{ number_format($total, 0, ',', ' ') }} XOF</span></div>
                    </div>
                    <button type="submit" class="w-full mt-4 bg-bmje-600 text-white py-3 rounded-lg font-medium hover:bg-bmje-700 transition">
                        Confirmer la commande
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
