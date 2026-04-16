<?php

namespace App\Notifications;

use App\Models\Entreprise;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EntrepriseApprouvee extends Notification
{
    use Queueable;

    public function __construct(public Entreprise $entreprise) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'entreprise_approuvee',
            'entreprise_id' => $this->entreprise->id,
            'message' => "Votre entreprise {$this->entreprise->raison_sociale} a ete approuvee. Vous pouvez commencer a publier vos produits.",
        ];
    }
}
