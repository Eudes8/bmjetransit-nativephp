<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BMJeTransit') - Marketplace & Livraison</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bmje: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' }
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Navbar --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-bmje-700">
                        BMJeTransit
                    </a>
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="{{ route('catalogue') }}" class="text-gray-600 hover:text-bmje-600 px-3 py-2 text-sm font-medium">Catalogue</a>
                        <a href="{{ route('tracking', ['numero' => '']) }}" class="text-gray-600 hover:text-bmje-600 px-3 py-2 text-sm font-medium">Suivi livraison</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-sm text-gray-600">{{ auth()->user()->nom_complet }}</span>
                        @if(auth()->user()->estAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-bmje-600 hover:text-bmje-700 text-sm font-medium">Admin</a>
                        @elseif(auth()->user()->estEntreprise())
                            <a href="{{ route('espace.dashboard') }}" class="text-bmje-600 hover:text-bmje-700 text-sm font-medium">Mon espace</a>
                        @endif
                        <a href="{{ route('client.commandes') }}" class="text-gray-600 hover:text-bmje-600 text-sm">Mes commandes</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 text-sm">Deconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-bmje-600 text-sm font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-bmje-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-bmje-700">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">BMJeTransit</h3>
                    <p class="text-sm text-gray-500">Marketplace et livraison integree en Cote d'Ivoire.</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">Entreprises</h3>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><a href="{{ route('register.entreprise') }}" class="hover:text-bmje-600">Devenir partenaire</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-bmje-600">Espace entreprise</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">Contact</h3>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><i class="fa-solid fa-phone mr-2"></i> +225 XX XX XX XX</li>
                        <li><i class="fa-solid fa-envelope mr-2"></i> contact@bmjetransit.com</li>
                        <li><i class="fa-solid fa-location-dot mr-2"></i> Abidjan, Cote d'Ivoire</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-8 pt-4 text-center text-xs text-gray-400">
                BMJeTransit &copy; {{ date('Y') }} - Tous droits reserves
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
