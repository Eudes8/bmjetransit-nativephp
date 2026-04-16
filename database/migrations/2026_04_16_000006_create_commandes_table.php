<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // BMJ-2026-00001
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');

            // Montants (XOF)
            $table->unsignedInteger('montant_produits')->default(0);
            $table->unsignedInteger('frais_livraison')->default(0);
            $table->unsignedInteger('montant_total')->default(0); // ce que le client paie
            $table->unsignedInteger('commission_bmje')->default(0);
            $table->unsignedInteger('montant_entreprise')->default(0); // ce que l'entreprise reçoit

            // Livraison
            $table->text('adresse_livraison');
            $table->string('ville_livraison')->default('Abidjan');
            $table->string('telephone_livraison');
            $table->decimal('lat_livraison', 10, 8)->nullable();
            $table->decimal('lng_livraison', 11, 8)->nullable();

            // Statut
            $table->enum('statut', [
                'en_attente',      // client vient de passer la commande
                'confirmee',       // entreprise a confirmé
                'en_preparation',  // entreprise prépare
                'prete',           // prête pour enlèvement
                'enlevee',         // livreur a enlevé
                'en_livraison',    // livreur en route
                'livree',          // livrée au client
                'annulee',         // annulée
                'litige',          // problème
            ])->default('en_attente');

            // Paiement
            $table->enum('mode_paiement', [
                'orange_money', 'mtn_momo', 'wave', 'especes', 'virement'
            ])->default('especes');
            $table->enum('paiement_statut', [
                'en_attente', 'paye', 'rembourse'
            ])->default('en_attente');

            $table->text('notes_client')->nullable();
            $table->timestamps();
        });

        Schema::create('commande_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantite')->default(1);
            $table->unsignedInteger('prix_unitaire')->default(0); // XOF
            $table->unsignedInteger('montant')->default(0); // quantité × prix
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_produits');
        Schema::dropIfExists('commandes');
    }
};
