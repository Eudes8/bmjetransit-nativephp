<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forfaits', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Starter, Pro, Premium
            $table->text('description')->nullable();
            $table->unsignedInteger('prix_mensuel')->default(0); // XOF
            $table->unsignedInteger('prix_annuel')->default(0);  // XOF
            $table->unsignedInteger('max_produits')->default(10);
            $table->boolean('a_statistiques')->default(false);
            $table->boolean('a_api')->default(false);
            $table->boolean('a_priorite')->default(false);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forfaits');
    }
};
