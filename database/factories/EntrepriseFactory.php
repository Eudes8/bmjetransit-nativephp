<?php

namespace Database\Factories;

use App\Models\Forfait;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntrepriseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->entreprise(),
            'forfait_id' => Forfait::inRandomOrder()->first()?->id ?? 1,
            'raison_sociale' => fake()->company(),
            'sigle' => strtoupper(fake()->lexify('???')),
            'secteur_activite' => fake()->randomElement([
                'Alimentation', 'Mode', 'Electronique', 'Cosmetique', 'Artisanat', 'Services',
            ]),
            'registre_commerce' => 'RC-CI-' . fake()->numerify('######'),
            'ville' => fake()->randomElement(['Abidjan', 'Bouake', 'Yamoussoukro']),
            'adresse' => fake()->address(),
            'description' => fake()->paragraph(),
            'statut' => 'approuvee',
            'commission_taux' => 10,
        ];
    }

    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => ['statut' => 'en_attente']);
    }
}
