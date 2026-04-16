<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCommande extends Notification
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
            'type' => 'nouvelle_commande',
            'commande_id' => $this->commande->id,
            'numero' => $this->commande->numero,
            'montant' => $this->commande->montant_total,
            'message' => "Nouvelle commande {$this->commande->numero} - " . number_format($this->commande->montant_total, 0, ',', ' ') . " XOF",
        ];
    }
}
