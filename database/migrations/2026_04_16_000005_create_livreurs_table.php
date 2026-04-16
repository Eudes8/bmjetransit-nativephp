<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livreurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_cni')->nullable();
            $table->string('permis_conduire')->nullable();
            $table->enum('type_vehicule', ['moto', 'velo', 'camionnette', 'camion'])->default('moto');
            $table->string('immatriculation')->nullable();
            $table->string('zone_activite')->default('Abidjan');
            $table->boolean('disponible')->default(true);
            $table->boolean('en_course')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedInteger('salaire_mensuel')->default(0); // XOF
            $table->unsignedInteger('prime_par_course')->default(500); // XOF
            $table->decimal('note_moyenne', 3, 2)->default(0);
            $table->unsignedInteger('nombre_courses')->default(0);
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livreurs');
    }
};
