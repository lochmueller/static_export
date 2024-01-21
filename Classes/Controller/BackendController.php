<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\PathService;
use FRUIT\StaticExport\Service\Publisher;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

#[Controller]
class BackendController extends ActionController
{
    protected $moduleTemplateFactory;
    protected $exporter;
    protected $publisher;
    protected $pathService;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        Exporter              $exporter,
        Publisher             $publisher,
        PathService           $pathService
    )
    {
        $this->publisher = $publisher;
        $this->exporter = $exporter;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->pathService = $pathService;
    }

    public function listAction(bool $export = false): ResponseInterface
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

    public function downloadAction(string $fileName): ResponseInterface
    {
        if (!\in_array($fileName, $this->getExports())) {
            throw new \Exception('Wrong filename', 123678);
        }

        // @todo Move to file response
        $path = $this->pathService->getArchiveFolder() . '/' . $fileName;

        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function publishAction(string $fileName): ResponseInterface
    {
        try {
            $this->publisher->publish($fileName);
            $this->addFlashMessage('Die Datei wurde auf dem Ziel Storage bereitgestellt.', 'Publish');
        } catch (\Exception $exception) {
            $this->addFlashMessage($exception->getMessage(), 'Publish', ContextualFeedbackSeverity::ERROR);
        }

        return new ForwardResponse('list');
    }

    public function deleteAction(string $fileName): ResponseInterface
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
            $this->addFlashMessage('Die Export-Datei konnte nicht entfernt werden. Grund: ' . $exception->getMessage(), 'Löschen', ContextualFeedbackSeverity::ERROR);
        }

        return new ForwardResponse('list');
    }

    protected function getExports(): array
    {
        $files = GeneralUtility::getFilesInDir($this->pathService->getArchiveFolder());
        $result = array_values((array)$files);
        sort($result);

        return array_reverse($result);
    }
}
