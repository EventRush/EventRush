<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary credentials
    |--------------------------------------------------------------------------
    */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', null),
        'api_key'    => env('CLOUDINARY_API_KEY', null),
        'api_secret' => env('CLOUDINARY_API_SECRET', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | URL de connexion (optionnel)
    |--------------------------------------------------------------------------
    | Format : cloudinary://<api_key>:<api_secret>@<cloud_name>
    */
    'url' => env('CLOUDINARY_URL', null),
    
    'cloud_url' => env('CLOUDINARY_URL', null),


    
    /*
    |--------------------------------------------------------------------------
    | Dossier par défaut pour les uploads
    |--------------------------------------------------------------------------
    */
    'folder' => env('CLOUDINARY_FOLDER', ''),
];
