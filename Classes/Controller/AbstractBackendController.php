<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\PathService;
use FRUIT\StaticExport\Service\Publisher;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

abstract class AbstractBackendController extends ActionController
{
    protected $exporter;
    protected $publisher;
    protected $pathService;

    public function __construct(
        Exporter    $exporter,
        Publisher   $publisher,
        PathService $pathService
    )
    {
        $this->publisher = $publisher;
        $this->exporter = $exporter;
        $this->pathService = $pathService;
    }

    public function listProcess(bool $export = false): array
    {
        if ($export) {
            $exportName = $this->exporter->export();
            $this->addFlashMessage('Der Export mit dem folgenden Dateinamen wurde angelegt: ' . $exportName, 'Create');
        }
        return $this->getExports();
    }

    public function downloadProcess(string $fileName)
    {
        if (!\in_array($fileName, $this->getExports())) {
            throw new \Exception('Wrong filename', 123678);
        }

        $path = $this->pathService->getArchiveFolder() . '/' . $fileName;

        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function publishProcess(string $fileName)
    {
        try {
            $this->publisher->publish($fileName);
            $this->addFlashMessage('Die Datei wurde auf dem Ziel Storage bereitgestellt.', 'Publish');
        } catch (\Exception $exception) {
            if ((new Typo3Version())->getMajorVersion() < 12) {
                $this->addFlashMessage($exception->getMessage(), 'Publish', AbstractMessage::ERROR);
            } else {
                $this->addFlashMessage($exception->getMessage(), 'Publish', ContextualFeedbackSeverity::ERROR);
            }
        }
    }

    public function deleteProcess(string $fileName)
    {
        try {
            $exports = $this->getExports();

            if (!in_array($fileName, $exports)) {
                throw new \Exception('Datei konnte nicht gefunden werden.', 123789123);
            }

            if (!unlink($this->pathService->getArchiveFolder() . '/' . $fileName)) {
                throw new \Exception('Datei konnte nicht gelöscht werden.', 1237891293231);
            }
            $this->addFlashMessage('Die Export-Datei wurde entfernt.', 'Löschen');
        } catch (\Exception $exception) {
            if ((new Typo3Version())->getMajorVersion() < 12) {
                $this->addFlashMessage('Die Export-Datei konnte nicht entfernt werden. Grund: ' . $exception->getMessage(), 'Löschen', AbstractMessage::ERROR);
            } else {
                $this->addFlashMessage('Die Export-Datei konnte nicht entfernt werden. Grund: ' . $exception->getMessage(), 'Löschen', ContextualFeedbackSeverity::ERROR);
            }

        }
    }

    protected function getExports(): array
    {
        $files = GeneralUtility::getFilesInDir($this->pathService->getArchiveFolder());
        $result = array_values((array)$files);
        sort($result);

        return array_reverse($result);
    }
}
