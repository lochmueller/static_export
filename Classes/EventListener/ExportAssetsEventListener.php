<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\CreateExportEvent;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExportAssetsEventListener
{

    public function __invoke(CreateExportEvent $event)
    {
        $publicDir = Environment::getPublicPath() . '/';
        $assetsCompressed = $publicDir . 'typo3temp/assets/compressed/';

        $files = (array) GeneralUtility::getAllFilesAndFoldersInPath(
            [],
            $assetsCompressed,
            'css,js',
            false
        );

        $files = GeneralUtility::removePrefixPathFromList($files, $publicDir);
        foreach ($files as $file) {
            $event->getZip()->addFile($publicDir . $file, $file);
        }
    }

}
