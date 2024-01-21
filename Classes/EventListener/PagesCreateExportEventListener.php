<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\CreateExportEvent;
use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\PathService;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PagesCreateExportEventListener
{
    public function __invoke(CreateExportEvent $event)
    {
        /** @var PathService $pathService */
        $pathService = GeneralUtility::makeInstance(PathService::class);
        $collectFolder = $pathService->getCollectFolder().'/';

        $files = (array)GeneralUtility::getAllFilesAndFoldersInPath(
            [],
            $collectFolder,
            '',
            true
        );

        $files = GeneralUtility::removePrefixPathFromList($files, $collectFolder);
        $files = array_filter($files);

        foreach ($files as $file) {
            if (is_dir($collectFolder . $file)) {
                $event->getZip()->addEmptyDir($file);
            } else {
                $event->getZip()->addFile($collectFolder . $file, $file);
            }
        }
    }
}
