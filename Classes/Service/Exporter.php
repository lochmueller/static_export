<?php


/**
 * BackendController.
 */
declare(strict_types=1);

namespace FRUIT\StaticExport\Service;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Exporter
{

    public function export()
    {

        $exportBaseDir = Environment::getProjectPath() . '/export';

        if (!is_dir($exportBaseDir)) {
            GeneralUtility::mkdir($exportBaseDir);
        }

        $exportName = 'export-' . date('Y-m-d-H-i-s');

        #if (!is_dir($exportBaseDir)) {
        #    GeneralUtility::mkdir($exportBaseDir);
        #}


        #DebuggerUtility::var_dump('Call');
        #die();
    }

}
