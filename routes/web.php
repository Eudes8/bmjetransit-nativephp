<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web — BMJeTransit
|--------------------------------------------------------------------------
|
| 3 espaces distincts :
| - /          → Vitrine publique + catalogue (clients)
| - /admin     → Back-office admin BMJE (NativePhP desktop)
| - /espace    → Espace entreprise partenaire
|
*/

// ── Pages publiques (clients) ─────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/catalogue', function () {
    return view('catalogue.index');
})->name('catalogue');

Route::get('/tracking/{numero}', function (string $numero) {
    return view('tracking.show', compact('numero'));
})->name('tracking');

// ── Auth ──────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/connexion', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/inscription', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/inscription/entreprise', function () {
        return view('auth.register-entreprise');
    })->name('register.entreprise');
});

// ── Espace Client (authentifié) ───────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/mes-commandes', function () {
        return view('client.commandes');
    })->name('client.commandes');

    Route::get('/mon-profil', function () {
        return view('client.profil');
    })->name('client.profil');
});

// ── Admin BMJE ────────────────────────────────────────

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/commandes', function () {
        return view('admin.commandes.index');
    })->name('commandes');

    Route::get('/entreprises', function () {
        return view('admin.entreprises.index');
    })->name('entreprises');

    Route::get('/livreurs', function () {
        return view('admin.livreurs.index');
    })->name('livreurs');

    Route::get('/finances', function () {
        return view('admin.finances.index');
    })->name('finances');

    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories');

    Route::get('/forfaits', function () {
        return view('admin.forfaits.index');
    })->name('forfaits');

    Route::get('/parametres', function () {
        return view('admin.parametres');
    })->name('parametres');
});

// ── Espace Entreprise ─────────────────────────────────

Route::middleware(['auth'])->prefix('espace')->name('espace.')->group(function () {

    Route::get('/', function () {
        return view('entreprise.dashboard');
    })->name('dashboard');

    Route::get('/produits', function () {
        return view('entreprise.produits.index');
    })->name('produits');

    Route::get('/commandes', function () {
        return view('entreprise.commandes.index');
    })->name('commandes');

    Route::get('/finances', function () {
        return view('entreprise.finances');
    })->name('finances');

    Route::get('/profil', function () {
        return view('entreprise.profil');
    })->name('profil');
});
