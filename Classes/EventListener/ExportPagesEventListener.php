<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\CreateExportEvent;
use FRUIT\StaticExport\Service\Exporter;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExportPagesEventListener
{
    public function __invoke(CreateExportEvent $event)
    {
        $pagesDir = Environment::getProjectPath() . Exporter::BASE_EXPORT_DIR . Exporter::COLLECT_FOLDER . '/';

        $files = (array)GeneralUtility::getAllFilesAndFoldersInPath(
            [],
            $pagesDir,
            '',
            true
        );

        $files = GeneralUtility::removePrefixPathFromList($files, $pagesDir);
        $files = array_filter($files);

        foreach ($files as $file) {
            if (is_dir($pagesDir . $file)) {
                $event->getZip()->addEmptyDir($file);
            } else {
                $event->getZip()->addFile($pagesDir . $file, $file);
            }
        }
    }
}
