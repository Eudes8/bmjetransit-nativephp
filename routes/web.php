<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CommandeController as AdminCommande;
use App\Http\Controllers\Admin\EntrepriseController as AdminEntreprise;
use App\Http\Controllers\Admin\LivreurController as AdminLivreur;
use App\Http\Controllers\Admin\FinanceController as AdminFinance;
use App\Http\Controllers\Admin\CategorieController as AdminCategorie;
use App\Http\Controllers\Client\CatalogueController;
use App\Http\Controllers\Client\CommandeController as ClientCommande;
use App\Http\Controllers\Client\PanierController;
use App\Http\Controllers\Client\TrackingController;
use App\Http\Controllers\Entreprise\DashboardController as EntrepriseDashboard;
use App\Http\Controllers\Entreprise\ProduitController;
use App\Http\Controllers\Entreprise\CommandeController as EntrepriseCommande;
use App\Http\Controllers\Entreprise\FinanceController as EntrepriseFinance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web - BMJeTransit
|--------------------------------------------------------------------------
|
| 3 espaces :
| /          -> Vitrine publique + catalogue (clients)
| /admin     -> Back-office admin BMJE (NativePhP desktop)
| /espace    -> Espace entreprise partenaire
|
*/

// -- Pages publiques --

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/catalogue/{produit}', [CatalogueController::class, 'show'])->name('catalogue.show');
Route::get('/boutique/{entreprise}', [CatalogueController::class, 'entreprise'])->name('catalogue.entreprise');

Route::get('/tracking/{numero?}', [TrackingController::class, 'show'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'rechercher'])->name('tracking.rechercher');

// -- Auth --

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
    Route::get('/inscription/entreprise', [AuthController::class, 'showRegisterEntreprise'])->name('register.entreprise');
    Route::post('/inscription/entreprise', [AuthController::class, 'registerEntreprise']);
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// -- Panier --

Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [PanierController::class, 'index'])->name('panier');
    Route::post('/panier/{produit}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::patch('/panier/{produit}', [PanierController::class, 'modifier'])->name('panier.modifier');
    Route::delete('/panier/{produit}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
    Route::delete('/panier', [PanierController::class, 'vider'])->name('panier.vider');
    Route::get('/commander', [PanierController::class, 'checkout'])->name('checkout');
});

// -- Espace Client --

Route::middleware(['auth'])->group(function () {
    Route::get('/mes-commandes', [ClientCommande::class, 'index'])->name('client.commandes');
    Route::post('/commander', [ClientCommande::class, 'store'])->name('commandes.store');
    Route::get('/mes-commandes/{commande}', [ClientCommande::class, 'show'])->name('client.commandes.show');
    Route::post('/mes-commandes/{commande}/annuler', [ClientCommande::class, 'annuler'])->name('client.commandes.annuler');
    Route::get('/mon-profil', function () {
        return view('client.profil');
    })->name('client.profil');
    Route::put('/mon-profil', [ClientCommande::class, 'updateProfil'])->name('client.profil.update');
});

// -- Admin BMJE --

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Commandes
    Route::get('/commandes', [AdminCommande::class, 'index'])->name('commandes');
    Route::get('/commandes/{commande}', [AdminCommande::class, 'show'])->name('commandes.show');
    Route::post('/commandes/{commande}/assigner', [AdminCommande::class, 'assignerLivreur'])->name('commandes.assigner');
    Route::post('/commandes/{commande}/statut', [AdminCommande::class, 'changerStatut'])->name('commandes.statut');

    // Entreprises
    Route::get('/entreprises', [AdminEntreprise::class, 'index'])->name('entreprises');
    Route::get('/entreprises/{entreprise}', [AdminEntreprise::class, 'show'])->name('entreprises.show');
    Route::post('/entreprises/{entreprise}/approuver', [AdminEntreprise::class, 'approuver'])->name('entreprises.approuver');
    Route::post('/entreprises/{entreprise}/suspendre', [AdminEntreprise::class, 'suspendre'])->name('entreprises.suspendre');
    Route::post('/entreprises/{entreprise}/rejeter', [AdminEntreprise::class, 'rejeter'])->name('entreprises.rejeter');
    Route::post('/entreprises/{entreprise}/commission', [AdminEntreprise::class, 'modifierCommission'])->name('entreprises.commission');

    // Livreurs
    Route::get('/livreurs', [AdminLivreur::class, 'index'])->name('livreurs');
    Route::get('/livreurs/ajouter', [AdminLivreur::class, 'create'])->name('livreurs.create');
    Route::post('/livreurs', [AdminLivreur::class, 'store'])->name('livreurs.store');
    Route::get('/livreurs/{livreur}', [AdminLivreur::class, 'show'])->name('livreurs.show');
    Route::post('/livreurs/{livreur}/disponibilite', [AdminLivreur::class, 'toggleDisponibilite'])->name('livreurs.disponibilite');
    Route::post('/livreurs/{livreur}/statut', [AdminLivreur::class, 'changerStatut'])->name('livreurs.statut');

    // Finances
    Route::get('/finances', [AdminFinance::class, 'index'])->name('finances');
    Route::post('/versements/{versement}/effectuer', [AdminFinance::class, 'effectuerVersement'])->name('versements.effectuer');

    // Categories
    Route::get('/categories', [AdminCategorie::class, 'index'])->name('categories');
    Route::post('/categories', [AdminCategorie::class, 'store'])->name('categories.store');
    Route::put('/categories/{categorie}', [AdminCategorie::class, 'update'])->name('categories.update');
    Route::delete('/categories/{categorie}', [AdminCategorie::class, 'destroy'])->name('categories.destroy');
});

// -- Espace Entreprise --

Route::middleware(['auth', 'role:entreprise'])->prefix('espace')->name('espace.')->group(function () {

    Route::get('/', [EntrepriseDashboard::class, 'index'])->name('dashboard');
    Route::get('/en-attente', [EntrepriseDashboard::class, 'enAttente'])->name('en_attente');

    // Produits
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('/produits/ajouter', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('/produits/{produit}/modifier', [ProduitController::class, 'edit'])->name('produits.edit');
    Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

    // Commandes
    Route::get('/commandes', [EntrepriseCommande::class, 'index'])->name('commandes');
    Route::get('/commandes/{commande}', [EntrepriseCommande::class, 'show'])->name('commandes.show');
    Route::post('/commandes/{commande}/confirmer', [EntrepriseCommande::class, 'confirmer'])->name('commandes.confirmer');
    Route::post('/commandes/{commande}/prete', [EntrepriseCommande::class, 'marquerPrete'])->name('commandes.prete');

    // Finances
    Route::get('/finances', [EntrepriseFinance::class, 'index'])->name('finances');
    Route::post('/versement', [EntrepriseFinance::class, 'demanderVersement'])->name('versement');

    Route::get('/profil', function () {
        return view('entreprise.profil');
    })->name('profil');
});
