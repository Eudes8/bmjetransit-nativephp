<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Entreprise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    public function definition(): array
    {
        $prix = fake()->numberBetween(500, 50000);

        return [
            'entreprise_id' => Entreprise::factory(),
            'categorie_id' => Categorie::inRandomOrder()->first()?->id ?? 1,
            'nom' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'prix' => $prix,
            'prix_promo' => fake()->boolean(30) ? intval($prix * 0.8) : null,
            'en_promo' => false,
            'stock' => fake()->numberBetween(0, 200),
            'poids_kg' => fake()->randomFloat(1, 0.1, 30),
            'est_fragile' => fake()->boolean(20),
            'statut' => 'actif',
        ];
    }
}
