<?php

namespace App\Providers;

use App\Services\PanierService;
use App\Services\PaiementService;
use App\Services\CommissionService;
use App\Services\LivraisonService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PanierService::class);
        $this->app->singleton(PaiementService::class);
        $this->app->singleton(CommissionService::class);
        $this->app->singleton(LivraisonService::class);
    }

    public function boot(): void
    {
        //
    }
}
