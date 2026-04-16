@extends('layouts.admin')

@section('title', 'Livreur - ' . $livreur->user->nom_complet)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.livreurs') }}" class="text-bmje-600 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Retour</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Profil --}}
        <div class="bg-white rounded-xl border p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-bmje-100 text-bmje-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">{{ $livreur->user->nom_complet }}</h2>
                <p class="text-sm text-gray-500">{{ $livreur->user->email }}</p>
                <p class="text-sm text-gray-500">{{ $livreur->user->telephone }}</p>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Vehicule</span>
                    <span class="capitalize font-medium">{{ $livreur->type_vehicule }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Zone</span>
                    <span class="font-medium">{{ $livreur->zone_activite }}</span>
                </div>
                @if($livreur->numero_permis)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Permis</span>
                        <span>{{ $livreur->numero_permis }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Total courses</span>
                    <span class="font-medium">{{ $livreur->nombre_courses }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Note moyenne</span>
                    <span class="font-medium">{{ $livreur->note_moyenne ? number_format($livreur->note_moyenne, 1) . '/5' : '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Disponible</span>
                    <form method="POST" action="{{ route('admin.livreurs.disponibilite', $livreur) }}">
                        @csrf
                        <button class="px-2 py-0.5 rounded-full text-xs font-medium {{ $livreur->disponible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $livreur->disponible ? 'Oui' : 'Non' }}
                        </button>
                    </form>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Statut</span>
                    @php $c = ['actif'=>'bg-green-100 text-green-700','inactif'=>'bg-gray-100 text-gray-500','suspendu'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$livreur->statut] ?? '' }}">{{ ucfirst($livreur->statut) }}</span>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                @foreach(['actif', 'suspendu', 'inactif'] as $s)
                    @if($livreur->statut !== $s)
                        <form method="POST" action="{{ route('admin.livreurs.statut', $livreur) }}">
                            @csrf
                            <input type="hidden" name="statut" value="{{ $s }}">
                            <button class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs hover:bg-gray-50">{{ ucfirst($s) }}</button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Dernieres livraisons --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Dernieres livraisons</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Tracking</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Commande</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Destination</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                            <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($livraisons as $liv)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-bmje-600">{{ $liv->numero_tracking }}</td>
                                <td class="px-6 py-3">{{ $liv->commande->numero }}</td>
                                <td class="px-6 py-3">{{ $liv->adresse_livraison }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst(str_replace('_',' ',$liv->statut)) }}</span>
                                </td>
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $liv->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune livraison.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
