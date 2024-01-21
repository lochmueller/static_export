<?php

if ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() < 12) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'StaticExport',
        'file',
        'tx_StaticExport',
        'bottom',
        [
            \FRUIT\StaticExport\Controller\Backend10Controller::class => 'list,download,publish,delete',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:static_export/Resources/Public/Icons/Extension.svg',
            'labels' => 'LLL:EXT:static_export/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
}