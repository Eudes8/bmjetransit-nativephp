@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-bmje-700 to-bmje-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Achetez. Nous livrons.</h1>
            <p class="text-xl text-bmje-100 mb-10 max-w-2xl mx-auto">
                Decouvrez des milliers de produits d'entreprises ivoiriennes. Livraison rapide partout a Abidjan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('catalogue') }}" class="bg-white text-bmje-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fa-solid fa-store mr-2"></i> Explorer le catalogue
                </a>
                <a href="{{ route('register.entreprise') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                    <i class="fa-solid fa-handshake mr-2"></i> Devenir partenaire
                </a>
            </div>
        </div>
    </div>

    {{-- Comment ca marche --}}
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-12">Comment ca marche</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-bmje-100 text-bmje-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">1. Trouvez vos produits</h3>
                <p class="text-gray-500 text-sm">Parcourez le catalogue des entreprises partenaires et ajoutez a votre panier.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-bmje-100 text-bmje-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">2. Commandez et payez</h3>
                <p class="text-gray-500 text-sm">Payez par Orange Money, MTN MoMo, Wave ou en especes a la livraison.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-bmje-100 text-bmje-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">3. Recevez chez vous</h3>
                <p class="text-gray-500 text-sm">Nos livreurs recuperent et livrent votre commande. Suivez en temps reel.</p>
            </div>
        </div>
    </div>

    {{-- Entreprises CTA --}}
    <div class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h2 class="text-2xl font-bold mb-4">Vous etes une entreprise ?</h2>
            <p class="text-gray-300 mb-8 max-w-xl mx-auto">
                Publiez vos produits sur BMJeTransit et atteignez des milliers de clients. Nous gerons toute la livraison.
            </p>
            <a href="{{ route('register.entreprise') }}" class="bg-bmje-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-bmje-700 transition inline-block">
                Inscrire mon entreprise
            </a>
        </div>
    </div>
@endsection
