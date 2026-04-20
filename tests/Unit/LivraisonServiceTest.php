<?php

namespace Tests\Unit;

use App\Services\LivraisonService;
use Tests\TestCase;

class LivraisonServiceTest extends TestCase
{
    public function test_calcul_frais_base(): void
    {
        $service = new LivraisonService();
        $frais = $service->calculerFrais(0, false);

        $this->assertIsInt($frais);
        $this->assertGreaterThan(0, $frais);
    }

    public function test_frais_augmentent_avec_distance(): void
    {
        $service = new LivraisonService();
        $frais_court = $service->calculerFrais(2, false);
        $frais_long = $service->calculerFrais(20, false);

        $this->assertGreaterThan($frais_court, $frais_long);
    }

    public function test_supplement_fragile(): void
    {
        $service = new LivraisonService();
        $frais_normal = $service->calculerFrais(5, false);
        $frais_fragile = $service->calculerFrais(5, true);

        $this->assertGreaterThan($frais_normal, $frais_fragile);
    }
}
