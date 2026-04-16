@extends('layouts.app')

@section('title', 'En attente')

@section('content')
<div class="max-w-lg mx-auto mt-20 px-4 text-center">
    <div class="bg-white rounded-xl border p-10">
        <div class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-800 mb-3">Votre demande est en cours d'examen</h1>
        <p class="text-gray-500 text-sm mb-6">
            L'equipe BMJeTransit examine votre dossier. Vous serez notifie des que votre entreprise sera approuvee.
            Cela prend generalement 24 a 48 heures.
        </p>
        @if($entreprise)
            <div class="bg-gray-50 rounded-lg p-4 text-left text-sm">
                <p class="text-gray-700"><strong>Entreprise :</strong> {{ $entreprise->raison_sociale }}</p>
                <p class="text-gray-700"><strong>Statut :</strong> {{ ucfirst(str_replace('_', ' ', $entreprise->statut)) }}</p>
                <p class="text-gray-700"><strong>Soumise le :</strong> {{ $entreprise->created_at->format('d/m/Y a H:i') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
