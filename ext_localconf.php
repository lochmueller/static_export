<?php

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('static_export');

$curlResolve = isset($configuration['curlResolve']) ? $configuration['curlResolve'] : '';

$configurations = GeneralUtility::trimExplode(',', (string)$curlResolve, true);

if (!empty($configurations)) {
    $GLOBALS['TYPO3_CONF_VARS']['HTTP']['curl'][\CURLOPT_RESOLVE] = $configurations;
}