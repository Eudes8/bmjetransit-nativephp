<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'prenom' => fake()->firstName(),
            'nom' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => '+225' . fake()->numerify('##########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'client',
            'ville' => fake()->randomElement(['Abidjan', 'Bouake', 'Yamoussoukro', 'San Pedro', 'Daloa']),
            'adresse' => fake()->address(),
            'est_actif' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function entreprise(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'entreprise']);
    }

    public function livreur(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'livreur']);
    }
}
