<?php

$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('static_export');

$curlResolve = isset($configuration['curlResolve']) ? $configuration['curlResolve'] : '';

$configurations = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', (string)$curlResolve, true);

if (!empty($configurations)) {
    $GLOBALS['TYPO3_CONF_VARS']['HTTP']['curl'][\CURLOPT_RESOLVE] = $configurations;
}