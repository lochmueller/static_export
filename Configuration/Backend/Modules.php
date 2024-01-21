<?php

use FRUIT\StaticExport\Controller\Backend12Controller;

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
            Backend12Controller::class => [
                'list',
                'download',
                'publish',
                'delete',
            ],
        ],
    ],
];