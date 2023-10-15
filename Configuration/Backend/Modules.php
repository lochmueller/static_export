<?php

return [
    'static_export' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user,group',
        'workspaces' => 'live',
        'path' => '/module/page/static-export',
        'labels' => 'LLL:EXT:static_export/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'StaticExport',
        'controllerActions' => [
            \FRUIT\StaticExport\Controller\BackendController::class => [
                'list',
                'download',
            ],
        ],
    ],
];