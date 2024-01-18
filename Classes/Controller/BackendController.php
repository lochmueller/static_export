<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\Publisher;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

#[Controller]
class BackendController extends ActionController
{
    protected $moduleTemplateFactory;
    protected $exporter;
    protected $publisher;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        Exporter $exporter,
        Publisher $publisher,
    ) {
        $this->publisher = $publisher;
        $this->exporter = $exporter;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    /**
     * Basic backend list.
     *
     * @param bool $export
     */
    public function listAction($export = false): \Psr\Http\Message\ResponseInterface
    {
        if ($export) {
            $exportName = $this->exporter->export();
            $this->addFlashMessage('Der Export mit dem folgenden Dateinamen wurde angelegt: ' . $exportName, 'Create');
        }
        $this->view->assignMultiple([
            'exports' => $this->getExports(),
        ]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * Basic backend list.
     *
     * @param string $fileName
     */
    public function downloadAction(string $fileName): \Psr\Http\Message\ResponseInterface
    {
        if (!\in_array($fileName, $this->getExports())) {
            throw new \Exception('Wrong filename', 123678);
        }

        // @todo Move to file response
        $path = $this->getExportBasePath() . '/' . $fileName;

        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function publishAction(string $fileName): \Psr\Http\Message\ResponseInterface
    {
        try {
            $this->publisher->publish($fileName);
            $this->addFlashMessage('Die Datei wurde auf dem Ziel Storage bereitgestellt.', 'Publish');
        } catch (\Exception $exception) {
            $this->addFlashMessage($exception->getMessage(), 'Publish', ContextualFeedbackSeverity::ERROR);
        }

        return new ForwardResponse('list');
    }

    protected function getExports(): array
    {
        $files = GeneralUtility::getFilesInDir($this->getExportBasePath());
        $result = array_values((array)$files);
        sort($result);

        return array_reverse($result);
    }

    protected function getExportBasePath(): string
    {
        return Environment::getProjectPath() . Exporter::BASE_EXPORT_DIR . Exporter::EXPORTS_FOLDER;
    }
}
