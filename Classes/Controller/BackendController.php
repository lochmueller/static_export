<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use TYPO3\CMS\Backend\Attribute\Controller;
use FRUIT\StaticExport\Service\Exporter;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
            $exportName = $this->exporter->export();
            $this->addFlashMessage('Der Export mit dem folgenden Dateinamen wurde angelegt: ' . $exportName, 'Create');
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

    public function publishAction(string $fileName): \Psr\Http\Message\ResponseInterface
    {
        $path = $this->getExportBasePath() . '/' . $fileName;
        if (!is_file($path) || !is_readable($path)) {
            $this->addFlashMessage('Die basis Datei existiert nicht oder kann nicht gelesen werden: ' . $path, 'Publish', ContextualFeedbackSeverity::ERROR);
            return new ForwardResponse('list');
        }

        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('static_export');
        $target = $configuration['publishTarget'] ?? '';

        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        try {
            $folder = $resourceFactory->getFolderObjectFromCombinedIdentifier($target);
        } catch (\Exception $exception) {
            $this->addFlashMessage('Probleme das Zielverzeichnis zu ermitteln: ' . $target, 'Publish', ContextualFeedbackSeverity::ERROR);
            return new ForwardResponse('list');
        }

        if ($folder->hasFile($fileName)) {
            $this->addFlashMessage('Die Datei gibt es bereits in dem Zielverzeichnis: ' . $target, 'Publish', ContextualFeedbackSeverity::WARNING);
            return new ForwardResponse('list');
        }

        $file = $folder->createFile($fileName);
        $file->setContents(file_get_contents($path));

        $this->addFlashMessage('Die Datei wurde auf dem Storage ' . $target . ' bereitgestellt.', 'Publish');

        return new ForwardResponse('list');
    }

    protected function getExports(): array
    {
        $files = GeneralUtility::getFilesInDir($this->getExportBasePath());
        $result =  array_values((array) $files);
        sort( $result);
        return array_reverse($result);
    }

    protected function getExportBasePath(): string
    {
        return Environment::getProjectPath() . Exporter::BASE_EXPORT_DIR . Exporter::EXPORTS_FOLDER;
    }
}
