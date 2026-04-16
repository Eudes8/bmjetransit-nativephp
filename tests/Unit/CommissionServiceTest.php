<?php

namespace Tests\Unit;

use App\Services\CommissionService;
use PHPUnit\Framework\TestCase;

class CommissionServiceTest extends TestCase
{
    public function test_calcul_commission_defaut(): void
    {
        $service = new CommissionService();
        $result = $service->calculer(10000);

        $this->assertEquals(1000, $result['commission']);
        $this->assertEquals(9000, $result['montant_entreprise']);
    }

    public function test_calcul_commission_personnalisee(): void
    {
        $service = new CommissionService();
        $result = $service->calculer(10000, 15);

        $this->assertEquals(1500, $result['commission']);
        $this->assertEquals(8500, $result['montant_entreprise']);
    }
}
