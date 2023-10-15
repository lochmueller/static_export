<?php

/**
 * $EM_CONF.
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Static Export',
    'description' => '',
    'category' => 'fe',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Tim Lochmüller',
    'author_email' => 'tim@fruit-lab.de',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'php' => '8.0.0-8.99.99',
            'staticfilecache' => '13.1.0-13.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'FRUIT\\StaticExport\\' => 'Classes/'
        ],
    ],
];
