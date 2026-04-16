<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Commande;
use Illuminate\Support\Str;

class PaiementService
{
    /**
     * Initier un paiement pour une commande
     */
    public function initierPaiement(Commande $commande, string $mode, string $numero_telephone): Transaction
    {
        $transaction = Transaction::create([
            'commande_id' => $commande->id,
            'reference' => 'TXN-' . strtoupper(Str::random(10)),
            'type' => 'paiement_commande',
            'montant' => $commande->montant_total,
            'mode' => $mode,
            'statut' => 'en_attente',
            'date_transaction' => now(),
            'metadata' => ['telephone' => $numero_telephone],
        ]);

        match ($mode) {
            'orange_money' => $this->initierOrangeMoney($transaction, $numero_telephone),
            'mtn_momo' => $this->initierMtnMomo($transaction, $numero_telephone),
            'wave' => $this->initierWave($transaction, $numero_telephone),
            'especes' => $this->marquerEspeces($transaction),
            default => null,
        };

        return $transaction;
    }

    /**
     * Orange Money - Initier le paiement
     */
    protected function initierOrangeMoney(Transaction $transaction, string $telephone): void
    {
        $config = config('bmje.paiement.orange_money');

        // TODO: Appel API Orange Money
        // POST {base_url}/webpayment
        // Headers: Authorization: Bearer {token}
        // Body: merchant_key, currency (XOF), order_id, amount, return_url, cancel_url, notif_url
        $transaction->update([
            'metadata' => array_merge($transaction->metadata ?? [], [
                'provider' => 'orange_money',
                'status' => 'initiated',
            ]),
        ]);
    }

    /**
     * MTN MoMo - Initier le paiement
     */
    protected function initierMtnMomo(Transaction $transaction, string $telephone): void
    {
        $config = config('bmje.paiement.mtn_momo');

        // TODO: Appel API MTN MoMo Collection
        // POST {base_url}/collection/v1_0/requesttopay
        // Headers: X-Reference-Id, X-Target-Environment, Ocp-Apim-Subscription-Key
        // Body: amount, currency (XOF), externalId, payer (partyIdType, partyId), payerMessage
        $transaction->update([
            'metadata' => array_merge($transaction->metadata ?? [], [
                'provider' => 'mtn_momo',
                'status' => 'initiated',
            ]),
        ]);
    }

    /**
     * Wave - Initier le paiement
     */
    protected function initierWave(Transaction $transaction, string $telephone): void
    {
        $config = config('bmje.paiement.wave');

        // TODO: Appel API Wave
        // POST {base_url}/checkout/sessions
        // Headers: Authorization: Bearer {api_key}
        // Body: amount, currency (XOF), client_reference, success_url, error_url
        $transaction->update([
            'metadata' => array_merge($transaction->metadata ?? [], [
                'provider' => 'wave',
                'status' => 'initiated',
            ]),
        ]);
    }

    /**
     * Especes - Paiement a la livraison
     */
    protected function marquerEspeces(Transaction $transaction): void
    {
        $transaction->update([
            'metadata' => array_merge($transaction->metadata ?? [], [
                'provider' => 'especes',
                'note' => 'Paiement a la livraison',
            ]),
        ]);
    }

    /**
     * Confirmer un paiement (callback des providers)
     */
    public function confirmerPaiement(Transaction $transaction): void
    {
        $transaction->update([
            'statut' => 'complete',
            'date_transaction' => now(),
        ]);

        $commande = $transaction->commande;
        $commande->update(['paiement_statut' => 'paye']);

        // Crediter le portefeuille de l'entreprise
        app(CommissionService::class)->crediterEntreprise($commande);
    }

    /**
     * Marquer un paiement comme echoue
     */
    public function echouerPaiement(Transaction $transaction, string $raison = null): void
    {
        $transaction->update([
            'statut' => 'echoue',
            'metadata' => array_merge($transaction->metadata ?? [], [
                'erreur' => $raison,
            ]),
        ]);
    }
}
