<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => false,
    'meta' => [
        'defaults' => [
            'title'        => 'Zone Ciné',
            'titleBefore'  => false, // page title affiché avant le suffixe "— Zone Ciné"
            'description'  => 'Catalogue complet de films et séries en français — streaming, bandes-annonces et fiches détaillées.',
            'separator'    => ' — ',
            'keywords'     => [],
            'canonical'    => 'current',
            'robots'       => 'index, follow',
        ],
        'webmaster_tags' => [
            'google'    => env('GOOGLE_SITE_VERIFICATION'),
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => 'Zone Ciné',
            'description' => 'Catalogue complet de films et séries en français — streaming, bandes-annonces et fiches détaillées.',
            'url'         => null, // utilise Url::current()
            'type'        => 'website',
            'site_name'   => 'Zone Ciné',
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => 'Zone Ciné',
            'description' => 'Catalogue complet de films et séries en français.',
            'url'         => 'current',
            'type'        => 'WebSite',
            'images'      => [],
        ],
    ],
];
