<?php

namespace App\Notifications;

use App\Models\Versement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VersementEffectue extends Notification
{
    use Queueable;

    public function __construct(public Versement $versement) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'versement_effectue',
            'versement_id' => $this->versement->id,
            'montant' => $this->versement->montant,
            'message' => "Versement de " . number_format($this->versement->montant, 0, ',', ' ') . " XOF effectue via " . ucfirst(str_replace('_', ' ', $this->versement->mode)),
        ];
    }
}
