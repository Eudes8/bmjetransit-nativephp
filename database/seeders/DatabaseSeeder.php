<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Entreprise;
use App\Models\Forfait;
use App\Models\Livreur;
use App\Models\Livraison;
use App\Models\PortefeuilleEntreprise;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin BMJE ────────────────────────────────
        User::create([
            'nom' => 'Beh',
            'prenom' => 'Jean Eudes',
            'email' => 'admin@bmjetransit.com',
            'telephone' => '+2250100000001',
            'password' => Hash::make('password'),
            'type' => 'admin',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        // ── Forfaits entreprises ──────────────────────
        $starter = Forfait::create([
            'nom' => 'Starter',
            'description' => 'Ideal pour demarrer. Publiez vos premiers produits gratuitement.',
            'prix_mensuel' => 0,
            'prix_annuel' => 0,
            'max_produits' => 10,
            'a_statistiques' => false,
            'a_api' => false,
            'a_priorite' => false,
        ]);

        Forfait::create([
            'nom' => 'Pro',
            'description' => 'Pour les entreprises en croissance. Plus de produits et statistiques.',
            'prix_mensuel' => 15000,
            'prix_annuel' => 150000,
            'max_produits' => 100,
            'a_statistiques' => true,
            'a_api' => false,
            'a_priorite' => false,
        ]);

        Forfait::create([
            'nom' => 'Premium',
            'description' => 'Tout illimite. Acces API, priorite sur les livraisons, support dedie.',
            'prix_mensuel' => 50000,
            'prix_annuel' => 500000,
            'max_produits' => 999999,
            'a_statistiques' => true,
            'a_api' => true,
            'a_priorite' => true,
        ]);

        // ── Categories principales ────────────────────
        $categories = [
            ['nom' => 'Alimentation & Boissons', 'icone' => 'fa-utensils', 'ordre' => 1],
            ['nom' => 'Electronique & High-Tech', 'icone' => 'fa-microchip', 'ordre' => 2],
            ['nom' => 'Mode & Vetements', 'icone' => 'fa-shirt', 'ordre' => 3],
            ['nom' => 'Maison & Decoration', 'icone' => 'fa-house', 'ordre' => 4],
            ['nom' => 'Sante & Beaute', 'icone' => 'fa-heart-pulse', 'ordre' => 5],
            ['nom' => 'Materiaux & BTP', 'icone' => 'fa-helmet-safety', 'ordre' => 6],
            ['nom' => 'Bureau & Fournitures', 'icone' => 'fa-briefcase', 'ordre' => 7],
            ['nom' => 'Agriculture', 'icone' => 'fa-seedling', 'ordre' => 8],
            ['nom' => 'Services', 'icone' => 'fa-wrench', 'ordre' => 9],
            ['nom' => 'Autres', 'icone' => 'fa-box', 'ordre' => 10],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // ── Client test ───────────────────────────────
        $client = User::create([
            'nom' => 'Kouame',
            'prenom' => 'Aya',
            'email' => 'client@test.com',
            'telephone' => '+2250100000002',
            'password' => Hash::make('password'),
            'type' => 'client',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        // ── Entreprise test ───────────────────────────
        $userEntreprise = User::create([
            'nom' => 'Traore',
            'prenom' => 'Ibrahim',
            'email' => 'entreprise@test.com',
            'telephone' => '+2250100000003',
            'password' => Hash::make('password'),
            'type' => 'entreprise',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        $entreprise = Entreprise::create([
            'user_id' => $userEntreprise->id,
            'forfait_id' => $starter->id,
            'raison_sociale' => 'Traore Electronics',
            'sigle' => 'TE',
            'secteur_activite' => 'Electronique',
            'registre_commerce' => 'RC-CI-123456',
            'ville' => 'Abidjan',
            'adresse' => 'Cocody Riviera 3',
            'description' => 'Vente de materiel electronique et high-tech.',
            'statut' => 'approuvee',
            'commission_taux' => 10,
        ]);

        PortefeuilleEntreprise::create([
            'entreprise_id' => $entreprise->id,
            'solde_disponible' => 45000,
            'solde_en_attente' => 12000,
            'total_gagne' => 150000,
            'total_retire' => 93000,
        ]);

        // ── Produits test ─────────────────────────────
        $produits = [
            ['nom' => 'Ecouteurs Bluetooth JBL', 'prix' => 15000, 'categorie_id' => 2, 'stock' => 50],
            ['nom' => 'Coque iPhone 15', 'prix' => 5000, 'categorie_id' => 2, 'stock' => 100],
            ['nom' => 'Chargeur rapide 65W', 'prix' => 8000, 'categorie_id' => 2, 'stock' => 30],
            ['nom' => 'Cable HDMI 2m', 'prix' => 3500, 'categorie_id' => 2, 'stock' => 80],
            ['nom' => 'Souris sans fil Logitech', 'prix' => 12000, 'categorie_id' => 2, 'stock' => 25],
            ['nom' => 'Cle USB 64Go', 'prix' => 4500, 'categorie_id' => 2, 'stock' => 60],
        ];

        foreach ($produits as $p) {
            Produit::create(array_merge($p, [
                'entreprise_id' => $entreprise->id,
                'statut' => 'actif',
            ]));
        }

        // ── 2e Entreprise test ────────────────────────
        $userEntreprise2 = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'boutique@test.com',
            'telephone' => '+2250100000006',
            'password' => Hash::make('password'),
            'type' => 'entreprise',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        $entreprise2 = Entreprise::create([
            'user_id' => $userEntreprise2->id,
            'forfait_id' => $starter->id,
            'raison_sociale' => 'Aminata Fashion',
            'sigle' => 'AF',
            'secteur_activite' => 'Mode',
            'ville' => 'Abidjan',
            'adresse' => 'Treichville Marche',
            'description' => 'Mode africaine et pret-a-porter.',
            'statut' => 'approuvee',
            'commission_taux' => 10,
        ]);

        $produits2 = [
            ['nom' => 'Robe wax Ankara', 'prix' => 25000, 'categorie_id' => 3, 'stock' => 20],
            ['nom' => 'Chemise bazin homme', 'prix' => 18000, 'categorie_id' => 3, 'stock' => 15],
            ['nom' => 'Sac a main cuir', 'prix' => 22000, 'categorie_id' => 3, 'stock' => 10],
        ];

        foreach ($produits2 as $p) {
            Produit::create(array_merge($p, [
                'entreprise_id' => $entreprise2->id,
                'statut' => 'actif',
            ]));
        }

        // ── Livreur test ──────────────────────────────
        $userLivreur = User::create([
            'nom' => 'Kone',
            'prenom' => 'Moussa',
            'email' => 'livreur@test.com',
            'telephone' => '+2250100000004',
            'password' => Hash::make('password'),
            'type' => 'livreur',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        $livreur = Livreur::create([
            'user_id' => $userLivreur->id,
            'numero_cni' => 'CI-0012345678',
            'permis_conduire' => 'PC-00123',
            'type_vehicule' => 'moto',
            'immatriculation' => 'AB-1234-CI',
            'zone_activite' => 'Abidjan',
            'disponible' => true,
            'salaire_mensuel' => 75000,
            'prime_par_course' => 500,
            'statut' => 'actif',
        ]);

        // ── Commande test ─────────────────────────────
        $commande = Commande::create([
            'numero' => 'BMJ-2026-TEST01',
            'client_id' => $client->id,
            'entreprise_id' => $entreprise->id,
            'montant_produits' => 20000,
            'frais_livraison' => 1500,
            'montant_total' => 21500,
            'commission_bmje' => 2000,
            'montant_entreprise' => 18000,
            'adresse_livraison' => 'Cocody Angre 7e Tranche',
            'ville_livraison' => 'Abidjan',
            'telephone_livraison' => '+2250100000002',
            'statut' => 'confirmee',
            'mode_paiement' => 'orange_money',
            'paiement_statut' => 'paye',
        ]);

        CommandeProduit::create([
            'commande_id' => $commande->id,
            'produit_id' => 1,
            'quantite' => 1,
            'prix_unitaire' => 15000,
            'montant' => 15000,
        ]);

        CommandeProduit::create([
            'commande_id' => $commande->id,
            'produit_id' => 2,
            'quantite' => 1,
            'prix_unitaire' => 5000,
            'montant' => 5000,
        ]);

        Livraison::create([
            'commande_id' => $commande->id,
            'livreur_id' => $livreur->id,
            'numero_tracking' => 'TRK-TEST01',
            'adresse_enlevement' => 'Cocody Riviera 3 - Traore Electronics',
            'adresse_livraison' => 'Cocody Angre 7e Tranche',
            'distance_km' => 5.5,
            'statut' => 'assignee',
            'prime_livreur' => 500,
        ]);

        // ── Affichage resume ──────────────────────────
        $this->command->info('');
        $this->command->info('=== BMJeTransit - Base initialisee ===');
        $this->command->info('');
        $this->command->info('  Comptes de test (mot de passe : password) :');
        $this->command->info('  Admin     : admin@bmjetransit.com');
        $this->command->info('  Client    : client@test.com');
        $this->command->info('  Entreprise: entreprise@test.com');
        $this->command->info('  Entreprise: boutique@test.com');
        $this->command->info('  Livreur   : livreur@test.com');
        $this->command->info('');
        $this->command->info('  3 forfaits, 10 categories, 9 produits');
        $this->command->info('  1 commande test, 1 livraison assignee');
        $this->command->info('');
    }
}
