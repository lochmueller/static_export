<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Service;

use FRUIT\StaticExport\Event\CreateExportEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Exporter
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function export(): string
    {
        /** @var PathService $pathService */
        $pathService = GeneralUtility::makeInstance(PathService::class);

        $exportName = 'export-' . date('Y-m-d-H-i-s');

        $zipFile = $pathService->getArchiveFolder() . '/' . $exportName . '.zip';

        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE);

        $event = new CreateExportEvent($zip);
        $this->eventDispatcher->dispatch($event);

        $event->getZip()->close();

        return $exportName . '.zip';
    }


    public function cleanup(int $keepLocalExportNumber)
    {
        // @todo implement
        die('nothing');
        if ($keepLocalExportNumber > 0) {

            /** @var PathService $pathService */
            $pathService = GeneralUtility::makeInstance(PathService::class);
            $archiveFolder  = $pathService->getArchiveFolder();


            var_dump($archiveFolder);
        }

    }

}
