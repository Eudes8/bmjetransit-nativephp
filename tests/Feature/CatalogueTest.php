<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_accessible(): void
    {
        $response = $this->get('/catalogue');
        $response->assertStatus(200);
    }

    public function test_page_accueil_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
