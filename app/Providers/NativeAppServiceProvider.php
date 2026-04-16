<?php

namespace App\Providers;

use Native\Desktop\Facades\Menu;
use Native\Desktop\Facades\Window;

class NativeAppServiceProvider
{
    /**
     * Exécuté une fois que l'application native est lancée.
     * Ici on configure la fenêtre principale du back-office admin.
     */
    public function boot(): void
    {
        // Menu de l'application
        Menu::create(
            Menu::app(),
            Menu::make('Fichier', [
                Menu::link(url('/admin'), 'Tableau de bord'),
                Menu::separator(),
                Menu::link(url('/admin/commandes'), 'Commandes'),
                Menu::link(url('/admin/entreprises'), 'Entreprises'),
                Menu::link(url('/admin/livreurs'), 'Livreurs'),
                Menu::separator(),
                Menu::link(url('/admin/finances'), 'Finances'),
            ]),
            Menu::make('Aide', [
                Menu::link('https://bmjetransit.com', 'Site web'),
                Menu::separator(),
                Menu::label('BMJeTransit v1.0.0'),
            ]),
        );

        // Fenêtre principale
        Window::open()
            ->title('BMJeTransit — Administration')
            ->width(1400)
            ->height(900)
            ->minWidth(1024)
            ->minHeight(768)
            ->route('admin.dashboard');
    }
}
