<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_connexion_accessible(): void
    {
        $response = $this->get('/connexion');
        $response->assertStatus(200);
    }

    public function test_page_inscription_accessible(): void
    {
        $response = $this->get('/inscription');
        $response->assertStatus(200);
    }

    public function test_inscription_client(): void
    {
        $response = $this->post('/inscription', [
            'prenom' => 'Jean',
            'nom' => 'Dupont',
            'email' => 'jean@test.com',
            'telephone' => '+2250101010101',
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
        ]);

        $response->assertRedirect('/catalogue');
        $this->assertDatabaseHas('users', ['email' => 'jean@test.com', 'role' => 'client']);
    }

    public function test_connexion_valide(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $response = $this->post('/connexion', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_connexion_invalide(): void
    {
        $user = User::factory()->create();

        $this->post('/connexion', [
            'email' => $user->email,
            'password' => 'mauvais',
        ]);

        $this->assertGuest();
    }

    public function test_deconnexion(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/deconnexion');
        $this->assertGuest();
    }
}
