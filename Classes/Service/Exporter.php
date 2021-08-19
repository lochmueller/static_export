<?php


/**
 * BackendController.
 */
declare(strict_types=1);

namespace FRUIT\StaticExport\Service;

use FRUIT\StaticExport\Event\CreateExportEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Exporter
{

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public const BASE_EXPORT_DIR = '/export';

    public const COLLECT_FOLDER = '/pages';

    public const EXPORTS_FOLDER = '/exports';

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function export()
    {
        $this->checkEnv();

        $exportBaseDir = Environment::getProjectPath() . self::BASE_EXPORT_DIR;
        $exportName = 'export-' . date('Y-m-d-H-i-s');

        #if (!is_dir($exportBaseDir)) {
        #    GeneralUtility::mkdir($exportBaseDir);
        #}

        $zipFile = $exportBaseDir . self::EXPORTS_FOLDER . '/' . $exportName . '.zip';


        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE);

        $event = new CreateExportEvent($zip);
        $this->eventDispatcher->dispatch($event);

        $event->getZip()->close();
        DebuggerUtility::var_dump($zipFile);
        die();
    }

    protected function checkEnv()
    {

        $exportBaseDir = Environment::getProjectPath() . '/export';

        if (!is_dir($exportBaseDir)) {
            GeneralUtility::mkdir($exportBaseDir);
        }

        if (!is_dir($exportBaseDir . self::EXPORTS_FOLDER)) {
            GeneralUtility::mkdir($exportBaseDir . self::EXPORTS_FOLDER);
        }

        if (!is_dir($exportBaseDir . self::COLLECT_FOLDER)) {
            GeneralUtility::mkdir($exportBaseDir . self::COLLECT_FOLDER);
        }

    }

}
