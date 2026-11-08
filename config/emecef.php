<?php

// config/emecef.php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode (test / production)
    |--------------------------------------------------------------------------
    */
    'env' => env('EMECEF_ENV', 'test'),  // 'test' ou 'production'

    /*
    |--------------------------------------------------------------------------
    | URLs de l'API
    |--------------------------------------------------------------------------
    */
    'invoice_url' => env('EMECEF_ENV', 'test') === 'production'
        ? 'https://sygmef.impots.bj/emcf/api/invoice'
        : 'https://developper.impots.bj/sygmef-emcf/api/invoice',

    'info_url' => env('EMECEF_ENV', 'test') === 'production'
        ? 'https://sygmef.impots.bj/emcf/api/info'
        : 'https://developper.impots.bj/sygmef-emcf/api/info',

    /*
    |--------------------------------------------------------------------------
    | Identifiants
    |--------------------------------------------------------------------------
    */
    'token' => env('EMECEF_TOKEN'),   // Jeton JWT fourni par la DGI
    'ifu'   => env('EMECEF_IFU'),    // IFU du contribuable (13 chiffres)
    'nim'   => env('EMECEF_NIM'),    // NIM de l'e-MCF (ex: XX01000001)

];