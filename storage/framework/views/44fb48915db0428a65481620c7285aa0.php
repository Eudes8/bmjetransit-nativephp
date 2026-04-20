<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'BMJeTransit'); ?> - Marketplace & Livraison</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        [v-cloak] { display: none !important; }
    </style>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col font-sans antialiased">

    <header class="fixed top-0 w-full z-50 bg-slate-50/80 backdrop-blur-xl shadow-sm border-b border-slate-200/50">
        <div class="flex justify-between items-center w-full px-6 h-16 max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <button class="text-primary active:scale-95 transition-transform duration-150 md:hidden">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2">
                    <h1 class="text-primary text-xl font-extrabold tracking-tighter">BMJeTransit</h1>
                </a>
                <nav class="hidden md:flex items-center ml-8 gap-1">
                    <a href="<?php echo e(route('catalogue')); ?>" class="px-4 py-2 rounded-lg text-sm font-semibold <?php echo e(request()->routeIs('catalogue*') ? 'text-primary bg-blue-50' : 'text-slate-600 hover:bg-slate-100'); ?>">Catalogue</a>
                    <a href="<?php echo e(route('tracking')); ?>" class="px-4 py-2 rounded-lg text-sm font-semibold <?php echo e(request()->routeIs('tracking*') ? 'text-primary bg-blue-50' : 'text-slate-600 hover:bg-slate-100'); ?>">Suivi</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <?php if(auth()->guard()->check()): ?>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-xs font-bold text-slate-900 leading-tight"><?php echo e(auth()->user()->nom_complet); ?></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e(auth()->user()->type); ?></span>
                        </div>
                        <div class="relative group">
                            <button class="w-10 h-10 rounded-full border-2 border-white shadow-sm overflow-hidden active:scale-95 transition-transform">
                                <?php if(auth()->user()->avatar): ?>
                                    <img src="<?php echo e(auth()->user()->avatar); ?>" alt="Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-blue-100 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                <?php endif; ?>
                            </button>

                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                <?php if(auth()->user()->estAdmin()): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard Admin
                                    </a>
                                <?php elseif(auth()->user()->estEntreprise()): ?>
                                    <a href="<?php echo e(route('espace.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-lg">dashboard</span> Mon Espace
                                    </a>
                                <?php elseif(auth()->user()->estLivreur()): ?>
                                    <a href="<?php echo e(route('livreur.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard Livreur
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('client.commandes')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <span class="material-symbols-outlined text-lg">shopping_bag</span> Mes Commandes
                                </a>
                                <hr class="my-1 border-slate-100">
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                        <span class="material-symbols-outlined text-lg">logout</span> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2">
                        <a href="<?php echo e(route('login')); ?>" class="text-slate-600 px-4 py-2 text-sm font-bold hover:text-primary transition-colors">Connexion</a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-blue-600/20 hover:opacity-90 active:scale-95 transition-all">Inscription</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>


    <main class="pt-16 flex-1 flex flex-col">

        <?php if(session('success')): ?>
            <div class="max-w-7xl mx-auto w-full px-6 mt-4">
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="text-sm font-semibold"><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="max-w-7xl mx-auto w-full px-6 mt-4">
                <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    <span class="text-sm font-semibold"><?php echo e(session('error')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>


    <?php if(!request()->is('espace*') && !request()->is('admin*') && !request()->is('livreur*')): ?>
    <footer class="bg-white border-t border-slate-100 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-primary text-xl font-extrabold tracking-tighter mb-4">BMJeTransit</h3>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                        La plateforme de référence pour le commerce et la logistique intégrée.
                        Achetez sereinement, nous nous occupons du reste.
                    </p>
                </div>
                <div>
                    <h4 class="text-slate-900 font-bold text-sm uppercase tracking-widest mb-4">Entreprises</h4>
                    <ul class="space-y-2 text-sm font-medium text-slate-500">
                        <li><a href="<?php echo e(route('register.entreprise')); ?>" class="hover:text-primary transition-colors">Devenir partenaire</a></li>
                        <li><a href="<?php echo e(route('login')); ?>" class="hover:text-primary transition-colors">Portail Marchand</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-slate-900 font-bold text-sm uppercase tracking-widest mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm font-medium text-slate-500">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">call</span> +225 XX XX XX XX</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-lg">mail</span> contact@bmje.ci</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-50 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                © <?php echo e(date('Y')); ?> BMJeTransit. Tous droits réservés.
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /app/resources/views/layouts/app.blade.php ENDPATH**/ ?>