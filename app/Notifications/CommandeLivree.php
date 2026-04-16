<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeLivree extends Notification
{
    use Queueable;

    public function __construct(public Commande $commande) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commande_livree',
            'commande_id' => $this->commande->id,
            'numero' => $this->commande->numero,
            'message' => "Votre commande {$this->commande->numero} a ete livree avec succes.",
        ];
    }
}
