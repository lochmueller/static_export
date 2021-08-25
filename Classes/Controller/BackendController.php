<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use FRUIT\StaticExport\Service\Exporter;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * BackendController.
 */
class BackendController extends ActionController
{

    /**
     * Basic backend list.
     * @param bool $export
     */
    public function listAction($export = false)
    {
        if ($export) {
            $this->objectManager->get(Exporter::class)->export();
        }
        $this->view->assignMultiple([
            'exports' => $this->getExports()
        ]);
    }

    /**
     * Basic backend list.
     * @param string $fileName
     */
    public function downloadAction(string $fileName)
    {
        if (!in_array($fileName, $this->getExports())) {
            throw new \Exception('Wrong filename', 123678);
        }

        $path = $this->getExportBasePath() . '/' . $fileName;

        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($path));
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\"");
        readfile($path);
        exit;
    }

    protected function getExports(): array
    {
        $files = GeneralUtility::getFilesInDir($this->getExportBasePath());
        return array_values((array) $files);
    }

    protected function getExportBasePath(): string
    {
        return Environment::getProjectPath() . Exporter::BASE_EXPORT_DIR . Exporter::EXPORTS_FOLDER;
    }
}
