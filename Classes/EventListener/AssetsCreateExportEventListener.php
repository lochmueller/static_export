<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\EventListener;

use FRUIT\StaticExport\Event\CreateExportEvent;
use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\PathService;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AssetsCreateExportEventListener
{
    const REGEX_PATHS = '/(href|src)="([^"]*)"/';

    const VALID_FILE_EXTENSIONS = [
        'gif',
        'png',
        'js',
        'css',
        'svg',
        'pdf',
    ];

    public function __invoke(CreateExportEvent $event)
    {
        $assets = $this->getValidAssets();
        $publicDir = Environment::getPublicPath() . '/';


        foreach ($assets as $asset) {
            $path = ltrim($asset, '/');
            if (is_file($publicDir . $path)) {
                $event->getZip()->addFile($publicDir . $path, $path);
            }
        }
    }

    protected function getValidAssets(): array
    {

        $assets = [];
        foreach ($this->getProcessedFiles() as $processedFile) {
            $html = GeneralUtility::getUrl($processedFile);
            if (preg_match_all(self::REGEX_PATHS, $html, $m)) {
                $assets = array_merge($assets, $m[2]);
            }
        }

        $assets = array_unique($assets);

        # Remove anchors
        $assets = array_filter($assets, function ($item) {
            return strpos($item, '#') === false;
        });

        # Remove query items
        $assets = array_filter($assets, function ($item) {
            return strpos($item, '?') === false;
        });

        $assets = array_filter($assets, function ($item) {
            return in_array(strtolower((string)pathinfo($item, PATHINFO_EXTENSION)), self::VALID_FILE_EXTENSIONS);
        });

        $assets = array_filter($assets, function ($item) {
            return $item[0] === '/';
        });

        return $assets;
    }

    protected function getProcessedFiles(): iterable
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
            if (is_file($collectFolder . $file)) {
                yield $collectFolder . $file;
            }
        }
    }
}
