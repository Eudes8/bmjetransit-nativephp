@extends('layouts.admin')

@section('title', 'Livreurs')

@section('header_actions')
    <a href="{{ route('admin.livreurs.create') }}" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">
        <i class="fa-solid fa-plus mr-1"></i> Ajouter un livreur
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Livreur</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Telephone</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Vehicule</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Zone</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Courses</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Disponible</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($livreurs as $liv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.livreurs.show', $liv) }}" class="text-bmje-600 font-medium hover:underline">{{ $liv->user->nom_complet }}</a>
                        </td>
                        <td class="px-6 py-3">{{ $liv->user->telephone }}</td>
                        <td class="px-6 py-3 capitalize">{{ $liv->type_vehicule }}</td>
                        <td class="px-6 py-3">{{ $liv->zone_activite }}</td>
                        <td class="px-6 py-3">{{ $liv->nombre_courses }}</td>
                        <td class="px-6 py-3">
                            <form method="POST" action="{{ route('admin.livreurs.disponibilite', $liv) }}" class="inline">
                                @csrf
                                <button class="px-2 py-0.5 rounded-full text-xs font-medium {{ $liv->disponible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $liv->disponible ? 'Oui' : 'Non' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-3">
                            @php $c = ['actif'=>'bg-green-100 text-green-700','inactif'=>'bg-gray-100 text-gray-500','suspendu'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c[$liv->statut] ?? '' }}">{{ ucfirst($liv->statut) }}</span>
                        </td>
                        <td class="px-6 py-3">
                            @if($liv->en_course)
                                <span class="text-orange-500 text-xs font-medium">En course</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Aucun livreur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $livreurs->withQueryString()->links() }}</div>
@endsection
