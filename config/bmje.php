<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nom de la plateforme
    |--------------------------------------------------------------------------
    */
    'nom' => env('BMJE_NOM', 'BMJE Transit'),

    /*
    |--------------------------------------------------------------------------
    | Commission par defaut (%)
    |--------------------------------------------------------------------------
    */
    'commission_defaut' => env('BMJE_COMMISSION', 10),

    /*
    |--------------------------------------------------------------------------
    | Frais de livraison
    |--------------------------------------------------------------------------
    */
    'livraison' => [
        'frais_base' => env('BMJE_LIVRAISON_BASE', 1500),
        'frais_km' => env('BMJE_LIVRAISON_KM', 200),
        'frais_fragile' => env('BMJE_LIVRAISON_FRAGILE', 500),
        'distance_max_km' => env('BMJE_LIVRAISON_MAX_KM', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | Versements
    |--------------------------------------------------------------------------
    */
    'versement' => [
        'montant_min' => env('BMJE_VERSEMENT_MIN', 5000),
        'delai_jours' => env('BMJE_VERSEMENT_DELAI', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Paiement mobile
    |--------------------------------------------------------------------------
    */
    'paiement' => [
        'modes' => ['orange_money', 'mtn_momo', 'wave', 'especes'],
        'orange_money' => [
            'merchant_id' => env('ORANGE_MONEY_MERCHANT_ID'),
            'api_key' => env('ORANGE_MONEY_API_KEY'),
            'api_secret' => env('ORANGE_MONEY_API_SECRET'),
            'base_url' => env('ORANGE_MONEY_URL', 'https://api.orange.com/orange-money-webpay/dev/v1'),
            'callback_url' => env('ORANGE_MONEY_CALLBACK'),
        ],
        'mtn_momo' => [
            'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
            'api_user' => env('MTN_MOMO_API_USER'),
            'api_key' => env('MTN_MOMO_API_KEY'),
            'base_url' => env('MTN_MOMO_URL', 'https://sandbox.momodeveloper.mtn.com'),
            'callback_url' => env('MTN_MOMO_CALLBACK'),
        ],
        'wave' => [
            'api_key' => env('WAVE_API_KEY'),
            'base_url' => env('WAVE_URL', 'https://api.wave.com/v1'),
            'callback_url' => env('WAVE_CALLBACK'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Zones de livraison
    |--------------------------------------------------------------------------
    */
    'zones' => [
        'Abidjan', 'Bouake', 'Daloa', 'Yamoussoukro', 'San-Pedro',
        'Korhogo', 'Man', 'Gagnoa', 'Divo', 'Abengourou',
    ],
];
