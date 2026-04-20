<?php $__env->startSection('title', 'Connexion'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto mt-16 px-4">
    <div class="bg-white rounded-xl shadow-sm border p-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Connexion</h2>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required autofocus
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" id="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-bmje-500 focus:border-transparent">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-bmje-600">
                    <span class="ml-2 text-sm text-gray-600">Se souvenir</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-bmje-600 text-white py-2.5 rounded-lg font-medium hover:bg-bmje-700 transition">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="<?php echo e(route('register')); ?>" class="text-bmje-600 hover:underline">S'inscrire</a>
            <span class="mx-1">ou</span>
            <a href="<?php echo e(route('register.entreprise')); ?>" class="text-bmje-600 hover:underline">Inscrire une entreprise</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/auth/login.blade.php ENDPATH**/ ?>