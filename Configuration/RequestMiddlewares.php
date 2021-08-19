<?php

use FRUIT\StaticExport\Middleware\CollectMiddleware;

return [
    'frontend' => [
        'static-export/collect' => [
            'target' => CollectMiddleware::class,
            'before' => [
                'staticfilecache/prepare',
            ],
            'after' => [
                'staticfilecache/generate',
            ],
        ],
    ],
];
