<?php



\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'static_export',
    'web',
    'static_export',
    '',
    [\FRUIT\StaticExport\Controller\BackendController::class => 'list'],
    [
        // Additional configuration
        'access' => 'user, group',
        'icon' => 'EXT:static_export/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:static_export/Resources/Private/Language/locallang_mod.xlf',
        'navigationComponentId' => '',
        'inheritNavigationComponentFromMainModule' => false,
    ]
);
