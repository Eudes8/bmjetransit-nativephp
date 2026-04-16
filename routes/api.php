<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogueController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EntrepriseController;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API - BMJeTransit Mobile
|--------------------------------------------------------------------------
|
| API REST pour l'app Android (clients, entreprises, livreurs)
| Authentification via Laravel Sanctum (Bearer token)
|
*/

// -- Auth --

Route::prefix('auth')->group(function () {
    Route::post('/inscription', [AuthController::class, 'register']);
    Route::post('/inscription/entreprise', [AuthController::class, 'registerEntreprise']);
    Route::post('/connexion', [AuthController::class, 'login']);
    Route::post('/mot-de-passe/oublie', [AuthController::class, 'forgotPassword']);
});

// -- Catalogue public --

Route::get('/catalogue', [CatalogueController::class, 'index']);
Route::get('/catalogue/{produit}', [CatalogueController::class, 'show']);
Route::get('/boutique/{entreprise}', [CatalogueController::class, 'entreprise']);
Route::get('/categories', [CatalogueController::class, 'categories']);
Route::get('/tracking/{numero}', [CatalogueController::class, 'tracking']);

// -- Routes authentifiees --

Route::middleware('auth:sanctum')->group(function () {

    // Profil
    Route::get('/profil', [AuthController::class, 'profil']);
    Route::put('/profil', [AuthController::class, 'updateProfil']);
    Route::post('/deconnexion', [AuthController::class, 'logout']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/lue', [NotificationController::class, 'marquerLue']);
    Route::post('/notifications/toutes-lues', [NotificationController::class, 'toutesLues']);

    // -- Client --
    Route::prefix('client')->group(function () {
        // Panier
        Route::get('/panier', [ClientController::class, 'panier']);
        Route::post('/panier/{produit}', [ClientController::class, 'ajouterPanier']);
        Route::patch('/panier/{produit}', [ClientController::class, 'modifierPanier']);
        Route::delete('/panier/{produit}', [ClientController::class, 'supprimerPanier']);
        Route::delete('/panier', [ClientController::class, 'viderPanier']);

        // Commandes
        Route::get('/commandes', [ClientController::class, 'commandes']);
        Route::post('/commandes', [ClientController::class, 'commander']);
        Route::get('/commandes/{commande}', [ClientController::class, 'commandeDetail']);
        Route::post('/commandes/{commande}/annuler', [ClientController::class, 'annulerCommande']);
        Route::post('/commandes/{commande}/avis', [ClientController::class, 'donnerAvis']);

        // Checkout
        Route::post('/checkout/frais', [ClientController::class, 'calculerFrais']);
    });

    // -- Entreprise --
    Route::middleware('role:entreprise')->prefix('entreprise')->group(function () {
        Route::get('/dashboard', [EntrepriseController::class, 'dashboard']);

        // Produits
        Route::get('/produits', [EntrepriseController::class, 'produits']);
        Route::post('/produits', [EntrepriseController::class, 'creerProduit']);
        Route::put('/produits/{produit}', [EntrepriseController::class, 'modifierProduit']);
        Route::delete('/produits/{produit}', [EntrepriseController::class, 'supprimerProduit']);

        // Commandes
        Route::get('/commandes', [EntrepriseController::class, 'commandes']);
        Route::get('/commandes/{commande}', [EntrepriseController::class, 'commandeDetail']);
        Route::post('/commandes/{commande}/confirmer', [EntrepriseController::class, 'confirmerCommande']);
        Route::post('/commandes/{commande}/prete', [EntrepriseController::class, 'marquerPrete']);

        // Finances
        Route::get('/finances', [EntrepriseController::class, 'finances']);
        Route::post('/versement', [EntrepriseController::class, 'demanderVersement']);
    });

    // -- Livreur --
    Route::middleware('role:livreur')->prefix('livreur')->group(function () {
        Route::get('/dashboard', [LivreurController::class, 'dashboard']);
        Route::get('/livraisons', [LivreurController::class, 'livraisons']);
        Route::get('/livraisons/{livraison}', [LivreurController::class, 'detail']);
        Route::post('/livraisons/{livraison}/accepter', [LivreurController::class, 'accepter']);
        Route::post('/livraisons/{livraison}/recuperee', [LivreurController::class, 'recuperee']);
        Route::post('/livraisons/{livraison}/en-route', [LivreurController::class, 'enRoute']);
        Route::post('/livraisons/{livraison}/livree', [LivreurController::class, 'livree']);
        Route::post('/disponibilite', [LivreurController::class, 'toggleDisponibilite']);
        Route::post('/position', [LivreurController::class, 'mettreAJourPosition']);
    });
});
