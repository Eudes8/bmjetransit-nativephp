<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('de_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vers_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['client_vers_livreur', 'client_vers_entreprise']);
            $table->unsignedTinyInteger('note'); // 1 à 5
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // nouvelle_commande, statut_change, paiement_recu...
            $table->string('titre');
            $table->text('message');
            $table->boolean('lue')->default(false);
            $table->timestamp('lue_le')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('avis');
    }
};
