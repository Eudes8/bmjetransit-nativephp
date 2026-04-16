<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - BMJeTransit</title>
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
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-bmje-900 text-white flex flex-col">
            <div class="p-6 border-b border-bmje-800">
                <h1 class="text-lg font-bold">BMJeTransit</h1>
                <p class="text-bmje-100 text-xs mt-1">Administration</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-chart-line w-5 mr-3"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.commandes') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.commandes*') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-shopping-cart w-5 mr-3"></i> Commandes
                </a>
                <a href="{{ route('admin.entreprises') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.entreprises*') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-building w-5 mr-3"></i> Entreprises
                </a>
                <a href="{{ route('admin.livreurs') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.livreurs*') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-motorcycle w-5 mr-3"></i> Livreurs
                </a>
                <a href="{{ route('admin.finances') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.finances*') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-wallet w-5 mr-3"></i> Finances
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.categories*') ? 'bg-bmje-800 text-white' : 'text-bmje-100 hover:bg-bmje-800' }}">
                    <i class="fa-solid fa-tags w-5 mr-3"></i> Categories
                </a>
            </nav>
            <div class="p-4 border-t border-bmje-800">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-bmje-700 rounded-full flex items-center justify-center text-sm">
                        {{ substr(auth()->user()->prenom, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->nom_complet }}</p>
                        <p class="text-xs text-bmje-100">Administrateur</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-bmje-100 hover:text-white text-xs">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Deconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">@yield('title')</h2>
                @yield('header_actions')
            </header>

            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
