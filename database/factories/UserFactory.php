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
            'type' => 'client',
            'statut' => 'actif',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'admin']);
    }

    public function entreprise(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'entreprise']);
    }

    public function livreur(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'livreur']);
    }
}
