<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use TYPO3\CMS\Backend\Attribute\Controller;
use FRUIT\StaticExport\Service\Exporter;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

#[Controller]
class BackendController extends ActionController
{
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected Exporter $exporter,
    ) {
    }

    /**
     * Basic backend list.
     * @param bool $export
     */
    public function listAction($export = false):\Psr\Http\Message\ResponseInterface
    {
        if ($export) {
            $this->exporter->export();
        }
        $this->view->assignMultiple([
            'exports' => $this->getExports()
        ]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * Basic backend list.
     * @param string $fileName
     */
    public function downloadAction(string $fileName):\Psr\Http\Message\ResponseInterface
    {
        if (!in_array($fileName, $this->getExports())) {
            throw new \Exception('Wrong filename', 123678);
        }

        // @todo Move to file response
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
