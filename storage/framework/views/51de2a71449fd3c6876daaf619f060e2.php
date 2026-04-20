<?php $__env->startSection('title', 'Marketplace - BMJeTransit'); ?>

<?php $__env->startSection('content'); ?>
<main class="pb-24">
    <!-- Search Bar Section -->
    <div class="px-6 py-4 bg-transparent max-w-5xl mx-auto w-full">
        <form action="<?php echo e(route('catalogue')); ?>" method="GET" class="flex flex-col w-full">
            <div class="flex w-full items-stretch rounded-lg h-12 bg-white shadow-sm border border-slate-100 focus-within:border-primary transition-colors overflow-hidden">
                <div class="text-slate-400 flex items-center justify-center pl-4">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input name="recherche" value="<?php echo e(request('recherche')); ?>" class="flex w-full border-none bg-transparent focus:ring-0 text-slate-900 placeholder:text-slate-400 px-4 text-sm font-medium" placeholder="Rechercher un produit..."/>
            </div>
        </form>
    </div>

    <!-- Hero Banner -->
    <div class="px-6 py-2 max-w-5xl mx-auto w-full">
        <div class="relative h-44 w-full overflow-hidden rounded-lg bg-primary shadow-lg shadow-primary/10">
            <img class="h-full w-full object-cover opacity-60" src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&q=80&w=1000"/>
            <div class="absolute inset-0 bg-gradient-to-r from-primary to-transparent flex flex-col justify-center px-6">
                <span class="text-white/80 font-bold text-[10px] uppercase tracking-[0.1em]">Offre Limitée</span>
                <h1 class="text-white text-2xl font-extrabold tracking-tight">Livraison Gratuite</h1>
                <p class="text-white/90 text-sm font-medium mt-1">Sur votre première commande</p>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="max-w-5xl mx-auto w-full">
        <div class="flex items-center justify-between px-6 pt-8 pb-3">
            <h3 class="text-slate-900 text-lg font-bold tracking-tight">Catégories</h3>
        </div>
        <div class="flex gap-3 px-6 overflow-x-auto no-scrollbar pb-2">
            <a href="<?php echo e(route('catalogue')); ?>" class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg <?php echo e(!request('categorie') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-white border border-slate-200 text-slate-600'); ?> px-5 active:scale-95 transition-all">
                <p class="text-sm font-bold tracking-wide">Tout</p>
            </a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('catalogue', ['categorie' => $cat->id])); ?>" class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg <?php echo e(request('categorie') == $cat->id ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-white border border-slate-200 text-slate-600'); ?> px-5 active:scale-95 transition-all">
                    <p class="text-sm font-semibold"><?php echo e($cat->nom); ?></p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="max-w-5xl mx-auto w-full">
        <div class="flex items-center justify-between px-6 pt-8 pb-4">
            <h3 class="text-slate-900 text-lg font-bold tracking-tight">
                <?php echo e(request('recherche') ? 'Résultats pour "' . request('recherche') . '"' : (request('categorie') ? 'Produits de la catégorie' : 'Produits vedettes')); ?>

            </h3>
            <span class="text-primary text-sm font-bold hover:underline cursor-pointer">Voir tout</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-6">
            <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('catalogue.show', $produit)); ?>" class="flex flex-col bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden active:scale-95 transition-transform duration-150">
                    <div class="aspect-square w-full bg-slate-50 relative">
                        <?php if($produit->images && count($produit->images) > 0): ?>
                            <img src="<?php echo e($produit->images[0]); ?>" alt="<?php echo e($produit->nom); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <div class="h-full w-full flex items-center justify-center text-slate-200">
                                <span class="material-symbols-outlined text-4xl">image</span>
                            </div>
                        <?php endif; ?>
                        <?php if($produit->en_promo): ?>
                            <span class="absolute top-2 left-2 bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">PROMO</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1 truncate"><?php echo e($produit->entreprise->raison_sociale); ?></p>
                        <h4 class="text-[13px] font-bold text-slate-800 truncate"><?php echo e($produit->nom); ?></h4>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-amber-400 text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-[11px] font-bold text-slate-600">4.8</span>
                        </div>
                        <p class="text-primary font-extrabold text-[15px] mt-2"><?php echo e(number_format($produit->prix_actuel, 0, ',', ' ')); ?> XOF</p>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-20 text-center text-slate-400">
                    <span class="material-symbols-outlined text-5xl mb-4">search_off</span>
                    <p class="text-lg font-bold">Aucun produit trouvé</p>
                    <p class="text-sm">Essayez une autre recherche ou parcourez les catégories.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="px-6 mt-8">
            <?php echo e($produits->links()); ?>

        </div>
    </div>
</main>


<nav class="fixed bottom-0 w-full z-50 rounded-t-2xl border-t border-slate-100 bg-white/90 backdrop-blur-lg shadow-[0_-4px_20px_rgba(0,0,0,0.03)] flex justify-around items-center h-20 pb-safe px-4">
    <a class="flex flex-col items-center justify-center text-primary scale-105 transition-transform" href="<?php echo e(route('catalogue')); ?>">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">storefront</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Marketplace</p>
    </a>
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors" href="<?php echo e(route('tracking')); ?>">
        <span class="material-symbols-outlined">local_shipping</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Suivi</p>
    </a>
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors" href="<?php echo e(route('client.commandes')); ?>">
        <span class="material-symbols-outlined">receipt</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Commandes</p>
    </a>
    <a class="flex flex-col items-center justify-center text-slate-400 opacity-80 hover:text-primary transition-colors" href="<?php echo e(route('client.profil')); ?>">
        <span class="material-symbols-outlined">person</span>
        <p class="font-label text-[11px] font-medium tracking-wide uppercase mt-1">Compte</p>
    </a>
</nav>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/catalogue/index.blade.php ENDPATH**/ ?>