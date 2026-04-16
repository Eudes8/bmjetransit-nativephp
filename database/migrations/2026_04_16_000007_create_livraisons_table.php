<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('livreur_id')->nullable()->constrained()->onDelete('set null');
            $table->string('numero_tracking')->unique(); // TRK-XXXXXX

            // Adresses
            $table->text('adresse_enlevement'); // chez l'entreprise
            $table->text('adresse_livraison');  // chez le client
            $table->decimal('distance_km', 8, 2)->nullable();

            // Statut
            $table->enum('statut', [
                'assignee',   // livreur assigné
                'enlevee',    // colis enlevé chez l'entreprise
                'en_route',   // en route vers le client
                'livree',     // livrée
                'echec',      // échec de livraison
                'retour',     // retour à l'entreprise
            ])->default('assignee');

            // Dates
            $table->timestamp('date_enlevement')->nullable();
            $table->timestamp('date_livraison_estimee')->nullable();
            $table->timestamp('date_livraison_reelle')->nullable();

            // Preuve
            $table->string('photo_preuve')->nullable();
            $table->string('signature')->nullable();
            $table->string('nom_receveur')->nullable();

            // Rémunération livreur
            $table->unsignedInteger('prime_livreur')->default(500); // XOF

            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('suivi_livraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livraison_id')->constrained()->onDelete('cascade');
            $table->string('statut');
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('horodatage');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_livraisons');
        Schema::dropIfExists('livraisons');
    }
};
