<?php

use FRUIT\StaticExport\Middleware\CollectMiddleware;

return [
    'frontend' => [
        'static-export/collect' => [
            'target' => CollectMiddleware::class,
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
            ],
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
    ],
];
