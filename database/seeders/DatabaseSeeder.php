<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Forfait;
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
            'telephone' => '+225 0000000001',
            'password' => Hash::make('password'),
            'type' => 'admin',
            'statut' => 'actif',
            'email_verified_at' => now(),
        ]);

        // ── Forfaits entreprises ──────────────────────
        Forfait::create([
            'nom' => 'Starter',
            'description' => 'Idéal pour démarrer. Publiez vos premiers produits gratuitement.',
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
            'description' => 'Tout illimité. Accès API, priorité sur les livraisons, support dédié.',
            'prix_mensuel' => 50000,
            'prix_annuel' => 500000,
            'max_produits' => 999999, // illimité
            'a_statistiques' => true,
            'a_api' => true,
            'a_priorite' => true,
        ]);

        // ── Catégories principales ────────────────────
        $categories = [
            ['nom' => 'Alimentation & Boissons', 'icone' => 'fa-utensils', 'ordre' => 1],
            ['nom' => 'Électronique & High-Tech', 'icone' => 'fa-microchip', 'ordre' => 2],
            ['nom' => 'Mode & Vêtements', 'icone' => 'fa-shirt', 'ordre' => 3],
            ['nom' => 'Maison & Décoration', 'icone' => 'fa-house', 'ordre' => 4],
            ['nom' => 'Santé & Beauté', 'icone' => 'fa-heart-pulse', 'ordre' => 5],
            ['nom' => 'Matériaux & BTP', 'icone' => 'fa-helmet-safety', 'ordre' => 6],
            ['nom' => 'Bureau & Fournitures', 'icone' => 'fa-briefcase', 'ordre' => 7],
            ['nom' => 'Agriculture', 'icone' => 'fa-seedling', 'ordre' => 8],
            ['nom' => 'Services', 'icone' => 'fa-wrench', 'ordre' => 9],
            ['nom' => 'Autres', 'icone' => 'fa-box', 'ordre' => 10],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        $this->command->info('Base de donnees initialisee avec succes !');
        $this->command->info('  Admin: admin@bmjetransit.com / password');
        $this->command->info('  3 forfaits crees (Starter, Pro, Premium)');
        $this->command->info('  10 categories creees');
    }
}
