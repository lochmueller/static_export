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
            'typo3' => '10.4.12-10.4.99',
            'php' => '7.3.0-7.4.99',
            'crawler' => '10.0.0-10.99.99',
            'staticfilecache' => '12.3.0-12.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'FRUIT\\StaticExport\\' => 'Classes/'
        ],
    ],
];
