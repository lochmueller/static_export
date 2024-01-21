<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Publisher
{
    public function publish(string $fileName)
    {

        /** @var PathService $pathService */
        $pathService = GeneralUtility::makeInstance(PathService::class);

        $path = $pathService->getArchiveFolder() . '/' . $fileName;
        if (!is_file($path) || !is_readable($path)) {
            throw new \Exception('Die basis Datei existiert nicht oder kann nicht gelesen werden: ' . $path, 2372183);
        }

        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('static_export');
        $target = $configuration['publishTarget'] ?? '';

        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        try {
            $folder = $resourceFactory->getFolderObjectFromCombinedIdentifier($target);
        } catch (\Exception $exception) {
            throw new \Exception('Probleme das Zielverzeichnis zu ermitteln: ' . $target, 345345);
        }

        if ($folder->hasFile($fileName)) {
            throw new \Exception('Die Datei gibt es bereits in dem Zielverzeichnis: ' . $target, 235432424);
        }

        $file = $folder->createFile($fileName);
        $file->setContents(file_get_contents($path));
    }
}
