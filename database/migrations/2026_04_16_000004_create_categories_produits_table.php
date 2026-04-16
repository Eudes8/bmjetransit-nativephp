<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('icone')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->unsignedInteger('prix')->default(0); // XOF
            $table->unsignedInteger('prix_promo')->nullable(); // XOF
            $table->boolean('en_promo')->default(false);
            $table->json('images')->nullable(); // tableau d'URLs
            $table->unsignedInteger('stock')->nullable(); // null = illimité
            $table->decimal('poids_kg', 8, 2)->nullable();
            $table->boolean('est_fragile')->default(false);
            $table->enum('statut', ['actif', 'inactif', 'en_rupture'])->default('actif');
            $table->decimal('note_moyenne', 3, 2)->default(0);
            $table->unsignedInteger('nombre_ventes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
        Schema::dropIfExists('categories');
    }
};
