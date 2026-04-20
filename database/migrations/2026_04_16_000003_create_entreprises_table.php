<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('forfait_id')->nullable();
            $table->string('raison_sociale');
            $table->string('sigle')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('registre_commerce')->nullable();
            $table->string('numero_contribuable')->nullable();
            $table->string('secteur_activite')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('Cote dIvoire');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->enum('statut', ['en_attente', 'approuvee', 'suspendue', 'rejetee'])->default('en_attente');
            $table->decimal('note_moyenne', 3, 2)->default(0);
            $table->unsignedInteger('nombre_ventes')->default(0);
            $table->decimal('commission_taux', 5, 2)->default(10.00); // % commission BMJE
            $table->timestamps();
        });

        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('forfait_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['actif', 'expire', 'annule'])->default('actif');
            $table->unsignedInteger('montant_paye')->default(0); // XOF
            $table->string('mode_paiement')->nullable();
            $table->boolean('renouvellement_auto')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
        Schema::dropIfExists('entreprises');
    }
};
