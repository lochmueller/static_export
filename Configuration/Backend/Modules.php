<?php

use FRUIT\StaticExport\Controller\BackendController;

return [
    'static_export' => [
        'parent' => 'file',
        'access' => 'user,group',
        'workspaces' => 'live',
        'path' => '/module/file/static-export',
        'labels' => 'LLL:EXT:static_export/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'StaticExport',
        'inheritNavigationComponentFromMainModule' => false,
        'controllerActions' => [
            BackendController::class => [
                'list',
                'download',
                'publish',
            ],
        ],
    ],
];