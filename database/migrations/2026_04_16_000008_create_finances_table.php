<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', [
                'paiement_client',        // client paie sa commande
                'commission_bmje',        // part BMJE
                'reversement_entreprise', // BMJE paie l'entreprise
                'abonnement',            // entreprise paie son forfait
                'salaire_livreur',       // salaire mensuel
                'prime_livreur',         // prime par course
            ]);
            $table->unsignedInteger('montant')->default(0); // XOF
            $table->foreignId('de_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vers_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('mode', [
                'orange_money', 'mtn_momo', 'wave', 'especes', 'virement'
            ])->nullable();
            $table->string('reference')->nullable(); // référence opérateur
            $table->enum('statut', ['en_attente', 'reussie', 'echouee', 'remboursee'])->default('en_attente');
            $table->timestamp('date_transaction')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('portefeuilles_entreprises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('solde_disponible')->default(0);  // XOF
            $table->unsignedInteger('solde_en_attente')->default(0);  // XOF
            $table->unsignedInteger('total_gagne')->default(0);       // XOF
            $table->unsignedInteger('total_retire')->default(0);      // XOF
            $table->timestamps();
        });

        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('montant')->default(0); // XOF
            $table->enum('mode', ['orange_money', 'mtn_momo', 'wave', 'virement'])->default('virement');
            $table->string('numero_compte')->nullable();
            $table->string('reference')->nullable();
            $table->enum('statut', ['en_attente', 'effectue', 'rejete'])->default('en_attente');
            $table->timestamp('date_demande');
            $table->timestamp('date_effectue')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versements');
        Schema::dropIfExists('portefeuilles_entreprises');
        Schema::dropIfExists('transactions');
    }
};
